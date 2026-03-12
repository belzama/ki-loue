<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Region;
use App\Models\Departement;
use Illuminate\Http\Request;

class LocalisationController extends Controller
{
    public function paysByContinent($continentId)
    {
        return Pays::where('continent_id', $continentId)
            ->orderBy('nom')
            ->get(['id','nom']);
    }

    public function regionsByPays($paysId)
    {
        return Region::where('pays_id', $paysId)
            ->orderBy('nom')
            ->get(['id','nom']);
    }

    public function departementsByRegion($regionId)
    {
        return Departement::where('region_id', $regionId)
            ->orderBy('nom')
            ->get(['id','nom']);
    }
}
