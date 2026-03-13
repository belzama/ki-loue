<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;

use App\Models\Devise;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Categorie;
use App\Models\Dispositif;
use App\Models\Publication;

use App\Services\TarifService;
use App\Services\TransactionService;

class PublicationController extends Controller
{   
    public function index(Request $request)
    {
        $categories = Categorie::orderBy('nom')->get();
        $pays = Pays::orderBy('nom')->get();

        $query = Publication::with([
                'dispositif.type_dispositif.categorie',
                'departement.region.pays',
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

        // Pays (via publication -> departement -> region)
        $query->when($request->pays_id, function ($q) use ($request) {
            $q->whereHas('departement.region', function ($qq) use ($request) {
                $qq->where('pays_id', $request->pays_id);
            });
        });

        // Région (via publication -> departement)
        $query->when($request->region_id, function ($q) use ($request) {
            $q->whereHas('departement', function ($qq) use ($request) {
                $qq->where('region_id', $request->region_id);
            });
        });

        // Departement (directement sur publication)
        $query->when($request->departement_id, function ($q) use ($request) {
            $q->where('departement_id', $request->departement_id);
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
            'departement.region',
            'devise'
        ]);

        return view('publications.show', compact('publication'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // 1. Règles de validation
        $rules = [
            'dispositif_id'  => 'required|exists:dispositifs,id',
            'departement_id' => 'required|exists:departements,id',
            'ville'          => 'required|string|max:150',
            'tarif_location' => 'required|numeric|min:1',
            'devise_id'      => 'required|exists:devises,id',
            'date_debut'     => 'required|date|after_or_equal:today',
            'date_fin'       => 'required|date|after:date_debut',
        ];

        $attributes = [
            'dispositif_id'  => 'Matériel',
            'departement_id' => 'Préfecture/Département',
            'ville'          => 'Ville/Localité',
            'tarif_location' => 'Tarif journalier',
            'devise_id'      => 'Devise',
            'date_debut'     => 'Date de début',
            'date_fin'       => 'Date de fin',
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // 2. Calculs via Service
        $prix_publication = TarifService::calculPrixPublication(
            $user->pays_id,
            $request->tarif_location,
            $request->date_debut,
            $request->date_fin
        );

        $bonus_accorde = min($user->solde_bonus, $prix_publication);
        $cout_publication = $prix_publication - $bonus_accorde;

        // 3. Vérification Solde
        if ($prix_publication > ($user->solde_reel + $user->solde_bonus)) {
            $montantARecharger = $prix_publication - ($user->solde_reel + $user->solde_bonus);

            // CRITIQUE : On stocke en session ICI pour que ce soit dispo en AJAX ET en classique
            session([
                'publication_pending' => $request->all(),
                'montant_a_recharger' => $montantARecharger
            ]);

            if ($request->ajax()) {
                return response()->json([
                    // On ajoute 'publication_info' dans le JSON pour plus de clarté
                    'publication_info' => true,
                    'errors' => ['solde' => ["Solde insuffisant. Manque : $montantARecharger"]],
                    'redirect' => route('user.transactions.deposit', ['user' => $user->id])
                ], 422);
            }

            return redirect()->route('user.transactions.deposit', ['user' => $user->id])
                ->with('publication_info', true)
                ->with('montant_a_recharger', $montantARecharger);
        }

        // 4. Transaction et Création
        try {
            DB::transaction(function () use ($request, $user, $prix_publication, $bonus_accorde, $cout_publication) {
                
                Publication::create([
                    'dispositif_id'   => $request->dispositif_id,
                    'departement_id'  => $request->departement_id,
                    'ville'           => $request->ville,
                    'devise_id'       => $request->devise_id,
                    'tarif_location'  => $request->tarif_location,
                    'prix_publication'=> $prix_publication,
                    'bonus_accorde'   => $bonus_accorde,
                    'cout_publication'=> $cout_publication,
                    'date_debut'      => $request->date_debut,
                    'date_fin'        => $request->date_fin,
                    'statut'          => 1 // Actif par défaut
                ]);

                if ($bonus_accorde > 0) {
                    TransactionService::execute($user, $bonus_accorde, 'retrait', 'paiement', 'Paiement publication (bonus)');
                }

                if ($cout_publication > 0) {
                    TransactionService::execute($user, $cout_publication, 'retrait', 'paiement', 'Paiement publication');
                }

                // Commissions (Sponsor et Perso)
                $this->distributeCommissions($user, $cout_publication);
            });

            $msg = 'Publication créée avec succès.';
            return $request->ajax() 
                ? response()->json(['success' => true, 'message' => $msg, 'redirect' => route('user.publications.index')])
                : redirect()->route('user.publications.index')->with('success', $msg);

        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['server' => ["Erreur lors de la création : " . $e->getMessage()]]], 500)
                : back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Gère la distribution des commissions sponsor/utilisateur
     */
    private function distributeCommissions($user, $montantBase)
    {
        if ($montantBase <= 0) return;

        // Sponsor (Parrain)
        if ($user->user && $user->user->taux_commission > 0) {
            $commSponsor = $montantBase * ($user->user->taux_commission / 100);
            TransactionService::execute($user->user, $commSponsor, 'depot', 'bonus', 'Commission sponsor publication');
        }

        // Commission personnelle (Cashback)
        if ($user->taux_commission_sponsor > 0) {
            $commUser = $montantBase * ($user->taux_commission_sponsor / 100);
            TransactionService::execute($user, $commUser, 'depot', 'bonus', 'Commission personnelle publication');
        }
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
        $validator = Validator::make($request->all(), [
            'departement_id' => 'required|exists:departements,id',
            'ville'          => 'required|string|max:150',
        ]);

        if ($validator->fails()) {
            return $request->ajax() 
                ? response()->json(['errors' => $validator->errors()], 422) 
                : back()->withErrors($validator);
        }

        $publication->update($request->only(['departement_id', 'ville']));

        $msg = 'Mise à jour réussie.';
        return $request->ajax()
            ? response()->json(['success' => true, 'message' => $msg, 'redirect' => route('user.publications.index')])
            : redirect()->route('user.publications.index')->with('success', $msg);
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        return redirect()->route('user.publications.index')
                        ->with('success', 'Publication supprimée avec succès.');
    }
}



