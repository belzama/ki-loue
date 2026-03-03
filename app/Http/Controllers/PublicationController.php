<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Devise;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Ville;
use App\Models\Categorie;
use App\Models\Dispositif;
use App\Models\Publication;
use App\Services\TransactionService;

class PublicationController extends Controller
{   
    public function index(Request $request)
    {
        $categories = Categorie::orderBy('nom')->get();
        $pays = Pays::orderBy('nom')->get();

        $query = Publication::with([
                'dispositif.type_dispositif.categorie',
                'ville.region.pays',
                'devise'
            ])
            ->whereHas('dispositif', function ($q) {
                $q->where('user_id', auth()->id());
            });

        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        // Catégorie
        $query->when($request->categorie_id, function ($q) use ($request) {
            $q->whereHas('dispositif.type_dispositif', function ($qq) use ($request) {
                $qq->where('categorie_id', $request->categorie_id);
            });
        });

        // Type
        $query->when($request->types_dispositif_id, function ($q) use ($request) {
            $q->whereHas('dispositif', function ($qq) use ($request) {
                $qq->where('type_dispositif_id', $request->types_dispositif_id);
            });
        });

        // Désignation
        $query->when($request->designation, function ($q) use ($request) {
            $q->whereHas('dispositif', function ($qq) use ($request) {
                $qq->where('designation', 'like', '%' . $request->designation . '%');
            });
        });

        // Statut
        $query->when($request->statut !== null && $request->statut !== '', function ($q) use ($request) {
            $q->where('statut', $request->statut);
        });

        // Pays (via publication -> ville -> region)
        $query->when($request->pays_id, function ($q) use ($request) {
            $q->whereHas('ville.region', function ($qq) use ($request) {
                $qq->where('pays_id', $request->pays_id);
            });
        });

        // Région (via publication -> ville)
        $query->when($request->region_id, function ($q) use ($request) {
            $q->whereHas('ville', function ($qq) use ($request) {
                $qq->where('region_id', $request->region_id);
            });
        });

        // Ville (directement sur publication)
        $query->when($request->ville_id, function ($q) use ($request) {
            $q->where('ville_id', $request->ville_id);
        });

        $publications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.publications.index', compact(
            'publications',
            'categories',
            'pays'
        ));
    }

    // Méthode standard create
    public function create()
    {
        $user = auth()->user();
        // Récupérer les dispositifs de l'utilisateur connecté
        $dispositifs = Dispositif::where('user_id', auth()->id())->get();

        $pays = Pays::orderBy('nom')->get();
        $devises = Devise::all();    // si tu as des devises

        return view('user.publications.create', compact('dispositifs', 'pays', 'devises'));

    }

    // Création à partir d'un dispositif
    public function createByDispositif(Dispositif $dispositif)
    {
        $pays = Pays::all();
        $devises = Devise::all();

        // On passe le dispositif sélectionné par défaut
        return view('user.publications.create', [
            'dispositifs' => [],
            'dispositif' => $dispositif,
            'pays' => $pays,
            'devises' => $devises,
        ]);
    }

    public function show(Publication $publication)
    {
        $publication->load([
            'dispositif.photos',
            'dispositif.type_dispositif',
            'ville.region',
            'devise'
        ]);

        return view('publications.show', compact('publication'));
    }

    public function store(Request $request)
    {
        $dispositif = Dispositif::findOrFail($request->dispositif_id);
        $user = $dispositif->user;

        $taux = $user->pays->taux_commission ?: sys_param('COMMISSION_RATE', 0);

        $prix_publication = $request->tarif_location * $taux / 100;

        $bonus_accorde = min($user->solde_bonus, $prix_publication);
        $cout_publication = $prix_publication - $bonus_accorde;

        /*
        ===========================
        VERIFICATION SOLDE
        ===========================
        */
        if ($prix_publication > ($user->solde_reel + $user->solde_bonus)) {

            $montantARecharger =
                $prix_publication - ($user->solde_reel + $user->solde_bonus);

            // On sauvegarde la demande en session
            session([
                'publication_pending' => $request->all()
            ]);

            return redirect()
                ->route('user.transactions.deposit', $user)
                ->with('warning', 'Solde insuffisant pour publier.')
                ->with('montant_a_recharger', $montantARecharger);
        }

        DB::transaction(function () use (
            $request,
            $user,
            $prix_publication,
            $bonus_accorde,
            $cout_publication
        ) {

            /*
            ===========================
            CREATION PUBLICATION
            ===========================
            */
            Publication::create([
                'dispositif_id'   => $request->dispositif_id,
                'ville_id'        => $request->ville_id,
                'devise_id'       => $request->devise_id,
                'tarif_location'  => $request->tarif_location,
                'prix_publication'=> $prix_publication,
                'bonus_accorde'   => $bonus_accorde,
                'cout_publication'=> $cout_publication,
                'date_debut'      => $request->date_debut,
                'date_fin'        => $request->date_fin,
            ]);

            /*
            ===========================
            DEBIT BONUS
            ===========================
            */
            if ($bonus_accorde > 0) {

                TransactionService::execute(
                    $user,
                    $bonus_accorde,
                    'retrait',
                    'paiement',
                    'Paiement publication (bonus)'
                );
            }

            /*
            ===========================
            DEBIT SOLDE REEL
            ===========================
            */
            if ($cout_publication > 0) {

                TransactionService::execute(
                    $user,
                    $cout_publication,
                    'retrait',
                    'paiement',
                    'Paiement publication'
                );
            }

            /*
            ===========================
            COMMISSION SPONSOR
            ===========================
            */
            if ($user->user) {

                $commissionSponsor = $cout_publication * ($user->user->taux_commission / 100);

                if ($commissionSponsor > 0) {

                    TransactionService::execute(
                        $user->user,
                        $commissionSponsor,
                        'depot',
                        'bonus',
                        'Commission sponsor publication'
                    );
                }
            }

            /*
            ===========================
            COMMISSION UTILISATEUR
            ===========================
            */
            $commissionUser = $cout_publication * ($user->taux_commission_sponsor / 100);

            if ($commissionUser > 0) {

                TransactionService::execute(
                    $user,
                    $commissionUser,
                    'depot',
                    'bonus',
                    'Commission personnelle publication'
                );
            }

        });

        return redirect()
            ->route('user.publications.index')
            ->with('success', 'Publication créée avec succès.');
    }

    public function edit(Publication $publication)
    {
        $pays = Pays::orderBy('nom')->get();
        $devises = Devise::all();
        $dispositifs = Dispositif::all(); // ou seulement ceux de l'utilisateur

        return view('user.publications.edit', compact(
            'publication',
            'pays',
            'devises',
            'dispositifs'
        ));
    }

    /**
     * Mettre à jour une publication existante.
     */
    public function update(Request $request, Publication $publication)
    {
        $data = $request->validate([
            //'dispositif_id' => 'required|exists:dispositifs,id',
            'ville_id' => 'required|exists:villes,id',
            /*'devise_id' => 'required|exists:devises,id',
            'tarif_location' => 'required|numeric|min:0',
            'prix_location' => 'required|numeric|min:0',
            'bonus_accorde' => 'required|numeric|min:0',
            'cout_location' => 'required|numeric|min:0',*/
        ]);

        // Vérifie que l'utilisateur possède le dispositif
        /*$dispositif = Dispositif::findOrFail($data['dispositif_id']);
        if ($dispositif->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }*/

        $publication->update([
            'ville_id' => $data['ville_id'],
            // on ne modifie pas date_debut/date_fin pour une mise à jour classique
        ]);

        return redirect()->route('user.publications.index')
                         ->with('success', 'Publication mise à jour avec succès.');
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        return redirect()->route('user.publications.index')
                        ->with('success', 'Publication supprimée avec succès.');
    }
}



