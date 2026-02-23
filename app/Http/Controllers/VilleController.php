<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pays;
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
        return view('admin.villes.create', compact('pays_list'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'nom' => 'required|string|max:255',
        ]);

        Ville::create($validated);

        return redirect()->route('admin.villes.index');
    }

    public function edit(Ville $ville)
    {
        $pays_list = Pays::all();
        return view('admin.villes.edit', compact('ville', 'pays_list'));
    }

    

    public function update(Request $request, Ville $ville)
    {
        $validated = $request->validate([
            'pays_id' => 'required|exists:pays,id',
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
