<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        return view('admin.regions.index', [
            'regions' => Region::with('pays')->get()
        ]);
    }

    public function create()
    {
        $pays_list = Pays::all();
        return view('admin.regions.create', compact('pays_list'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'nom' => 'required|string|max:150',
        ]);

        Region::create($validated);

        return redirect()->route('admin.regions.index');
    }

    public function edit(Region $region)
    {
        $pays_list = Pays::all();
        return view('admin.regions.edit', compact('region', 'pays_list'));
    }

    

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'nom' => 'required|string|max:255',
        ]);

        $region->update($validated);

        return redirect()->route('admin.regions.index');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return back();
    }
}
