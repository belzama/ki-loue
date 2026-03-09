<?php

namespace App\Http\Controllers;

use App\Models\TypesDispositif;
use App\Models\TypeDispositifParam;
use App\Models\Categorie;
use Illuminate\Http\Request;

class TypesDispositifController extends Controller
{
    public function search(Request $request)
    {
        $query = TypesDispositif::with('categorie');

        // Filtre par catégorie si fourni
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par recherche
        if ($request->filled('q')) {
            $query->where('nom', 'like', '%' . $request->q . '%');
        }

        $types = $query->limit(50)->get();

        return response()->json([
            'results' => $types->map(function ($type) {
                return [
                    'id' => $type->id,
                    'text' => $type->nom . (optional($type->categorie)->nom ? ' (' . $type->categorie->nom . ')' : ''),
                    'categorie_id' => $type->categorie_id,
                ];
            }),
        ]);
    }

    // ⚡ Récupérer un type par ID (pour Select2 + remplir catégorie)
    public function show($id)
    {
        $type = TypesDispositif::with('categorie')->findOrFail($id);

        return response()->json([
            'id' => $type->id,
            'nom' => $type->nom,
            'categorie_id' => $type->categorie_id,
            'categorie_nom' => $type->categorie->nom ?? ''
        ]);
    }

    // ⚡ Récupérer les paramètres dynamiques d’un type
    public function params($id)
    {
        $type = TypesDispositif::with('params')->findOrFail($id);

        return response()->json([
            'params' => $type->params,
            'nb_max_photo' => $type->nb_max_photo
        ]);
    }

    public function index()
    {
        $types = TypesDispositif::with(['categorie','params'])->paginate(10);
        return view('admin.types_dispositifs.index', compact('types'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('admin.types_dispositifs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'tarif_min' => 'required|numeric|min:0',
            'tarif_max' => 'required|numeric|gte:tarif_min',
            'nb_max_photo' => 'required|numeric|min:1',
            'params.*.name' => 'nullable|string|max:255',
            'params.*.value_type' => 'nullable|in:string,decimal,date,datetime',
            'params.*.required' => 'nullable|boolean',
        ]);

        $type = TypesDispositif::create($data);

        // Enregistrer les paramètres
        if ($request->has('params')) {
            foreach ($request->params as $param) {

                if (!empty($param['name'])) {
                    $type->params()->create([
                        'name' => $param['name'],
                        'label' => $param['label'],
                        'value_type' => $param['value_type'],
                        'list_values' => $param['list_values'] ?? null,
                        'numeric_value_unit' => $param['numeric_value_unit'] ?? null,
                        'required' => (bool) $param['required'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.types_dispositifs.index')
            ->with('success', 'Type créé avec succès.');
    }

    public function edit(TypesDispositif $types_dispositif)
    {
        $categories = Categorie::all();
        $types_dispositif->load('params');

        return view('admin.types_dispositifs.edit',
            compact('types_dispositif', 'categories'));
    }

    public function update(Request $request, TypesDispositif $types_dispositif)
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'tarif_min' => 'required|numeric|min:0',
            'tarif_max' => 'required|numeric|gte:tarif_min',
            'nb_max_photo' => 'required|numeric|min:1',
            'params.*.name' => 'nullable|string|max:255',
            'params.*.value_type' => 'nullable|in:string,decimal,date,datetime',
            'params.*.required' => 'nullable|boolean',
        ]);

        $types_dispositif->update($data);

        // Supprimer anciens paramètres
        $types_dispositif->params()->delete();

        // Réinsérer les nouveaux
        if ($request->has('params')) {
            foreach ($request->params as $param) {

                if (!empty($param['name'])) {
                    $types_dispositif->params()->create([
                        'name' => $param['name'],
                        'label' => $param['label'],
                        'value_type' => $param['value_type'],
                        'list_values' => $param['list_values'] ?? null,
                        'numeric_value_unit' => $param['numeric_value_unit'] ?? null,
                        'required' => (bool) $param['required'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.types_dispositifs.index')
            ->with('success', 'Type modifié avec succès.');
    }

    public function destroy(TypesDispositif $types_dispositif)
    {
        $types_dispositif->delete();
        return back()->with('success', 'Type supprimé.');
    }    

    public function typesByCategorie($categorieId)
    {
        return TypesDispositif::where('categorie_id', $categorieId)
            ->orderBy('nom')
            ->get(['id','nom']);
    }
}
