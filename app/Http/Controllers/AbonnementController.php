<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Dispositif;
use App\Models\Periodicite;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbonnementController extends Controller
{
    public function index()
    {
        $abonnements = Abonnement::whereHas('dispositif', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with('dispositif')
            ->latest()
            ->paginate(10);

        return view('user.abonnements.index', compact('abonnements'));
    }

    public function create()
    {
        $user = auth()->user();
        // Récupérer les dispositifs de l'utilisateur connecté
        $dispositifs = Dispositif::where('user_id', auth()->id())->get();
        $periodicites = Periodicite::all();
        return view('user.abonnements.create', compact('dispositifs', 'periodicites'));
    }

    public function createByDispositif(Dispositif $dispositif)
    {
        $periodicites = Periodicite::all();
        return view('user.abonnements.create', compact('dispositif', 'periodicites'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'dispositif_id'  => 'required|exists:dispositifs,id',
            'periodicite_id' => 'required|exists:periodicites,id',
            'date_debut'     => 'required|date',
            'date_fin'       => 'required|date|after_or_equal:date_debut',
            'montant'        => 'nullable|numeric|min:0',
            'actif'          => 'boolean',
        ];

        $attributes = [
            'dispositif_id'  => 'Matériel',
            'periodicite_id' => 'Périodicité',
            'date_debut'     => 'Date de début',
            'date_fin'       => 'Date de fin',
            'montant'        => 'Montant',
            'actif'          => 'Actif',
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $montant = (float) $request->montant;

        // Vérification solde
        if ($montant > ($user->solde_reel + $user->solde_bonus)) {

            $montantARecharger = $montant - ($user->solde_reel + $user->solde_bonus);

            session([
                'abonnement_pending'  => $request->only([
                    'dispositif_id', 'periodicite_id', 'date_debut', 'date_fin', 'montant', 'actif',
                ]),
                'montant_a_recharger' => $montantARecharger,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'abonnement_pending' => true,
                    'errors'   => ['solde' => ["Solde insuffisant. Manque : $montantARecharger"]],
                    'redirect' => route('user.transactions.deposit', ['user' => $user->id]),
                ], 422);
            }

            return redirect()->route('user.transactions.deposit', ['user' => $user->id])
                ->with('abonnement_info', true)
                ->with('montant_a_recharger', $montantARecharger);
        }

        // Solde suffisant -> création directe
        try {
            $this->createAbonnement($user, $request->only([
                'dispositif_id', 'periodicite_id', 'date_debut', 'date_fin', 'montant', 'actif',
            ]));

            $msg = 'Abonnement créé avec succès.';

            return $request->ajax()
                ? response()->json(['success' => true, 'message' => $msg, 'redirect' => route('user.abonnements.index')])
                : redirect()->route('user.abonnements.index')->with('success', $msg);

        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['server' => [$e->getMessage()]]], 500)
                : back()->with('error', $e->getMessage());
        }
    }

    /**
     * Logique partagée : création de l'abonnement + débit + commissions.
     * Réutilisée par store() ET par la reprise après recharge.
     */
    public function createAbonnement($user, array $data)
    {
        $montant = (float) $data['montant'];

        $bonus_accorde   = min($user->solde_bonus, $montant);
        $cout_abonnement = $montant - $bonus_accorde;

        DB::transaction(function () use ($user, $data, $montant, $bonus_accorde, $cout_abonnement) {

            Abonnement::create([
                'dispositif_id'  => $data['dispositif_id'],
                'periodicite_id' => $data['periodicite_id'],
                'date_debut'     => $data['date_debut'],
                'date_fin'       => $data['date_fin'],
                'montant'        => $montant,
                'actif'          => 1,
            ]);

            if ($bonus_accorde > 0) {
                TransactionService::execute($user, $bonus_accorde, 'retrait', 'paiement', 'Paiement abonnement (bonus)');
            }

            if ($cout_abonnement > 0) {
                TransactionService::execute($user, $cout_abonnement, 'retrait', 'paiement', 'Paiement abonnement');
            }

            $this->distributeCommissions($user, $cout_abonnement);
        });
    }

    /**
     * Distribue les commissions liées au paiement d'un abonnement :
     * - Commission "sponsor" (parrain direct)
     * - Commission "personnelle" (crédit bonus pour l'utilisateur lui-même)
     */
    protected function distributeCommissions($user, float $cout_abonnement)
    {
        if ($cout_abonnement <= 0) {
            return;
        }

        // Commission pour le parrain (sponsor)
        if ($user->parrain && $user->parrain->taux_commission_sponsor > 0) {
            $commissionSponsor = $cout_abonnement * ($user->parrain->taux_commission_sponsor / 100);

            if ($commissionSponsor > 0) {
                TransactionService::execute(
                    $user->parrain,
                    $commissionSponsor,
                    'depot',
                    'commission',
                    'Commission sur abonnement de ' . $user->nom . ' ' . $user->prenom
                );
            }
        }

        // Commission personnelle (cashback / bonus)
        if (!empty($user->taux_commission_perso) && $user->taux_commission_perso > 0) {
            $commissionPerso = $cout_abonnement * ($user->taux_commission_perso / 100);

            if ($commissionPerso > 0) {
                TransactionService::execute(
                    $user,
                    $commissionPerso,
                    'depot',
                    'commission',
                    'Commission personnelle sur abonnement'
                );
            }
        }
    }

    public function show(Dispositif $dispositif)
    {
        $abonnement = Abonnement::where('dispositif_id', $dispositif->id)->firstOrFail();
        return view('user.abonnements.show', compact('abonnement', 'dispositif'));
    }

    public function edit(Dispositif $dispositif)
    {
        $abonnement  = Abonnement::where('dispositif_id', $dispositif->id)->firstOrFail();
        $dispositifs = Dispositif::all();
        return view('user.abonnements.edit', compact('abonnement', 'dispositif', 'dispositifs'));
    }

    public function update(Request $request, Dispositif $dispositif)
    {
        $abonnement = Abonnement::where('dispositif_id', $dispositif->id)->firstOrFail();

        $validated = $request->validate([
            'dispositif_id' => 'required|exists:dispositifs,id',
            'date_debut'    => 'required|date',
            'date_fin'      => 'required|date|after_or_equal:date_debut',
            'montant'       => 'nullable|numeric|min:0',
            'actif'         => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif', true);

        $abonnement->update($validated);

        return redirect()->route('abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function destroy(Dispositif $dispositif)
    {
        $abonnement = Abonnement::where('dispositif_id', $dispositif->id)->firstOrFail();
        $abonnement->delete();

        return redirect()->route('abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }
}