<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\ModePaiement;
use App\Models\User;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        // Dates par défaut = aujourd’hui
        $dateDebut = $request->date_debut ?? Carbon::today()->toDateString();
        $dateFin   = $request->date_fin ?? Carbon::today()->toDateString();

        $transactions = Transaction::where('user_id', $user->id)
            ->whereBetween('created_at', [
                Carbon::parse($dateDebut)->startOfDay(),
                Carbon::parse($dateFin)->endOfDay()
            ])
            ->latest()
            ->paginate(15);

        return view('user.transactions.index', compact(
            'transactions',
            'dateDebut',
            'dateFin'
        ));
    }

    public function show(Transaction $transaction)
    {
        return view('user.transactions.show', compact('transaction'));
    }

    public function deposit(User $user)
    {
        // récupérer les modes de paiement pour le pays de l'utilisateur passé en param
        $modes = ModePaiement::where('pays_id', $user->pays_id)->get();

        return view('user.transactions.deposit', compact('modes', 'user'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100'
        ]);

        $user = Auth::user();

        TransactionService::execute(
            $user,
            $request->montant,
            'depot',
            'recharge',
            'Recharge du compte'
        );

        // Recharger le solde mis à jour
        $user->refresh();

        /*
        ===========================
        REPRISE PUBLICATION EN ATTENTE
        ===========================
        */
        if (session()->has('publication_pending')) {

            $data = session('publication_pending');
            session()->forget('publication_pending');

            return app(\App\Http\Controllers\PublicationController::class)
                    ->store(new Request($data));
        }

        /*
        ===========================
        REPRISE ABONNEMENT EN ATTENTE
        ===========================
        */
        if (session()->has('abonnement_pending')) {

            $data    = session('abonnement_pending');
            $montant = (float) ($data['montant'] ?? 0);

            session()->forget(['abonnement_pending', 'montant_a_recharger']);

            // Vérifier que le solde est désormais suffisant
            if ($montant <= ($user->solde_reel + $user->solde_bonus)) {
                try {
                    app(\App\Http\Controllers\AbonnementController::class)
                        ->createAbonnement($user, $data);

                    return redirect()->route('user.abonnements.index')
                        ->with('success', 'Recharge effectuée et abonnement créé avec succès.');

                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', "Recharge effectuée, mais erreur lors de la création de l'abonnement : " . $e->getMessage());
                }
            }

            // Solde toujours insuffisant
            $manque = $montant - ($user->solde_reel + $user->solde_bonus);

            return redirect()->back()
                ->with('warning', "Recharge effectuée, mais le solde reste insuffisant pour finaliser l'abonnement. Il manque encore " . number_format($manque, 0, ',', ' ') . " FCFA.");
        }
        
        return redirect()->back()
            ->with('success','Recharge effectuée.');
    }

    public function retrait()
    {
        return view('user.transactions.retrait');
    }

    public function storeRetrait(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100'
        ]);

        try {

            TransactionService::execute(
                Auth::user(),
                $request->montant,
                'retrait',
                'retrait',
                'Retrait utilisateur'
            );

        } catch (\Exception $e) {
            return back()->with('error',$e->getMessage());
        }

        return redirect()->route('user.transactions.index')
            ->with('success','Retrait effectué.');
    }

    /*
    |--------------------------------------------------------------------------
    | SYSTEME (appel interne)
    |--------------------------------------------------------------------------
    */

    public static function paiementPublication(User $user, $montant, $reference)
    {
        return TransactionService::execute(
            $user,
            $montant,
            'retrait',
            'paiement',
            'Paiement publication',
            $reference
        );
    }

    public static function paiementReservation(User $user, $montant, $reference)
    {
        return TransactionService::execute(
            $user,
            $montant,
            'retrait',
            'paiement',
            'Paiement réservation',
            $reference
        );
    }

    public static function remboursement(User $user, $montant, $reference)
    {
        return TransactionService::execute(
            $user,
            $montant,
            'depot',
            'remboursement',
            'Remboursement',
            $reference
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function bonus(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:1'
        ]);

        $user = User::findOrFail($request->user_id);

        TransactionService::execute(
            $user,
            $request->montant,
            'depot',
            'bonus',
            'Bonus administrateur'
        );

        return back()->with('success','Bonus ajouté.');
    }

    public function ajustement(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:1',
            'type' => 'required|in:depot,retrait',
            'description' => 'required'
        ]);

        $user = User::findOrFail($request->user_id);

        TransactionService::execute(
            $user,
            $request->montant,
            $request->type,
            'ajustement',
            $request->description
        );

        return back()->with('success','Ajustement effectué.');
    }
}
