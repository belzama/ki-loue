<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Tarif;

class TarifService
{
    public static function calculPrixPublication(
        int $pays_id,
        float $tarif_location,
        string $date_debut,
        string $date_fin
    ): float {

        $dateDebut = Carbon::parse($date_debut);
        $dateFin   = Carbon::parse($date_fin);

        $joursTotal = $dateDebut->diffInDays($dateFin);
        
        $tarifs = Tarif::where('pays_id', $pays_id)
            ->orderBy('tranche_debut')
            ->get();

        $prix = 0;

        foreach ($tarifs as $tarif) {

            $debutTranche = $tarif->tranche_debut;
            $finTranche   = $tarif->tranche_fin;

            if ($joursTotal < $debutTranche) {
                continue;
            }

            $joursDansTranche = min($joursTotal, $finTranche) - $debutTranche + 1;

            if ($joursDansTranche > 0) {

                $prix += $joursDansTranche * ($tarif_location * $tarif->tranche_valeur);
            }
        }

        return round($prix, 2);
    }
}