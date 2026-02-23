<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devise;

class DeviseController extends Controller
{
    public function index()
    {
        return view('admin.devises.index', [
            'devises' => Devise::all()
        ]);
    }

    public function create()
    {
        return view('admin.devises.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'libelle' => 'required|string|max:255',
        ]);

        Devise::create($validated);

        return redirect()->route('admin.devises.index');
    }

    public function edit(Devise $devise)
    {
        return view('admin.devises.edit', compact('devise'));
    }

    

    public function update(Request $request, Devise $devise)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'libelle' => 'required|string|max:255',
        ]);

        $devise->update($validated);

        return redirect()->route('admin.devises.index');
    }

    public function destroy(Devise $devise)
    {
        $devise->delete();
        return back();
    }
}
