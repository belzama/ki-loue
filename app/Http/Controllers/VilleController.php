<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Ville;



class VilleController extends Controller
{
    public function index()
    {
        return view('admin.villes.index', [
            'villes' => Ville::all()
        ]);
    }

    public function create()
    {
        $pays_list = Pays::all();
        $regions = Region::all();
        return view('admin.villes.create', compact('pays_list', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'nom' => 'required|string|max:255',
        ]);

        Ville::create($validated);

        return redirect()->route('admin.villes.index');
    }

    public function edit(Ville $ville)
    {
        $pays_list = Pays::all();
        $regions = Region::all();
        return view('admin.villes.edit', compact('ville', 'pays_list', 'regions'));
    }

    

    public function update(Request $request, Ville $ville)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'nom' => 'required|string|max:255',
        ]);

        $ville->update($validated);

        return redirect()->route('admin.villes.index');
    }

    public function destroy(Ville $ville)
    {
        $ville->delete();
        return back();
    }
}
