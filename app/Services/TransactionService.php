<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public static function execute(
        User $user,
        float $montant,
        string $type,
        string $categorie,
        string $description = null,
        string $reference = null
    )
    {
        return DB::transaction(function () use (
            $user, $montant, $type, $categorie, $description, $reference
        ) {

            $user->lockForUpdate();
            
            $solde = 0;

            switch ($type) {

                // ======================
                // DEPOT (entrée)
                // ======================
                case 'depot':

                    if ($categorie === 'bonus') {
                        $user->increment('solde_bonus', $montant);
                        $solde = $user->solde_bonus;

                    } else {
                        $user->increment('solde_reel', $montant);
                        $solde = $user->solde_reel;
                    }

                    break;


                // ======================
                // RETRAIT (sortie)
                // ======================
                case 'retrait':

                    if ($user->solde_reel + $user->solde_bonus < $montant) {
                        throw new \Exception('Solde insuffisant');
                    }

                    if ($categorie === 'bonus') {
                        $user->decrement('solde_bonus', $montant);
                        $solde = $user->solde_bonus;

                    } else {
                        $user->decrement('solde_reel', $montant);
                        $solde = $user->solde_reel;
                    }

                    break;
            }

            $user->save();

            return Transaction::create([
                'user_id' => $user->id,
                'montant' => $montant,
                'type' => $type,
                'categorie' => $categorie,
                'reference' => $reference,
                'statut' => 'effectuee',
                'solde_apres' => $solde,
                'description' => $description,
                'created_by' => auth()->id()
            ]);
        });
    }

    public function updateSoldeReel($montant)
    {
        throw new \Exception('Modification directe interdite. Utiliser TransactionService.');
    }

    public function updateSoldeBonus($montant)
    {
        throw new \Exception('Modification directe interdite. Utiliser TransactionService.');
    }

}
