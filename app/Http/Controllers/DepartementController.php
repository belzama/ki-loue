<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Departement;



class DepartementController extends Controller
{
    public function index()
    {
        return view('admin.departements.index', [
            'departements' => Departement::all()
        ]);
    }

    public function create()
    {
        $pays_list = Pays::all();
        $regions = Region::all();
        return view('admin.departements.create', compact('pays_list', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'nom' => 'required|string|max:255',
        ]);

        Departement::create($validated);

        return redirect()->route('admin.departements.index');
    }

    public function edit(Departement $departement)
    {
        $pays_list = Pays::all();
        $regions = Region::all();
        return view('admin.departements.edit', compact('departement', 'pays_list', 'regions'));
    }

    

    public function update(Request $request, Departement $departement)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'nom' => 'required|string|max:255',
        ]);

        $departement->update($validated);

        return redirect()->route('admin.departements.index');
    }

    public function destroy(Departement $departement)
    {
        $departement->delete();
        return back();
    }
}
