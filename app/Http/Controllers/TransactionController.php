<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('user.transactions.index', compact('transactions','user'));
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

        TransactionService::execute(
            Auth::user(),
            $request->montant,
            'depot',
            'recharge',
            'Recharge du compte'
        );

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
