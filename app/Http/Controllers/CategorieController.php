<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Categorie::orderBy('nom')->get()
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'image' => (isset($category) ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_link'] = $path;
        } elseif (isset($category)) {
            unset($validated['image_link']); // ne pas écraser l'image existante si aucun nouveau fichier
        }

        $category->fill($validated)->save();

        //Categorie::create($validated);

        return redirect()->route('admin.categories.index');
    }

    public function edit(Categorie $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    

    public function update(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // supprimer l'ancienne image si elle existe
            if ($category->image_link) {
                \Storage::disk('public')->delete($category->image_link);
            }
            $validated['image_link'] = $request->file('image')->store('categories', 'public');
        }

        unset($validated['image']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Categorie $category)
    {
        $category->delete();
        return back();
    }
}

