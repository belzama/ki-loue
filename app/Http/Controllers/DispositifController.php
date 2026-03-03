<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Categorie;
use App\Models\Dispositif;
use App\Models\TypesDispositif;

class DispositifController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin,User']);
    }

    public function index(Request $request)
    {
        $categories = Categorie::orderBy('nom')->get();
        $types = TypesDispositif::orderBy('nom')->get();

        $dispositifs = Dispositif::with(['type_dispositif.categorie', 'photos'])
            ->where('user_id', auth()->id())
            ->when($request->categorie_id, function ($q) use ($request) {
                $q->whereHas('type_dispositif', function ($q2) use ($request) {
                    $q2->where('categorie_id', $request->categorie_id);
                });
            })
            ->when($request->types_dispositif_id, function ($q) use ($request) {
                $q->where('types_dispositif_id', $request->types_dispositif_id);
            })
            ->latest()
            ->paginate(10);

        return view('user.dispositifs.index', compact('categories', 'types', 'dispositifs'));
    }

    public function create()
    {
        $categories = Categorie::all();
        $types = TypesDispositif::with('params')->get();
        return view('user.dispositifs.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'types_dispositif_id'    => 'required|exists:types_dispositifs,id',
            'numero_immatriculation' => 'nullable|string',
            'designation'            => 'required|string',
            'description'            => 'nullable|string',
            'statut'                 => 'required|in:Actif,Inactif,Suspendu',
            'etat'                   => 'required|in:Neuf,Bon,Révisé',
            'photos.*'               => 'image|max:2048',
            'params'                 => 'nullable|array',
            'params.*'               => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $dispositif = Dispositif::create($data);

        // Enregistrement des paramètres par ID
        if ($request->has('params')) {
            foreach ($request->params as $paramId => $value) {
                 if (!is_numeric($paramId)) {
                    continue; // Ignore toute clé invalide
                }

                if (!empty($value)) {
                    $dispositif->params()->create([
                        'type_dispositif_param_id' => $paramId,
                        'value'                    => $value
                    ]);
                }
            }
        }

        if ($request->hasFile('photos')) {
            foreach($request->file('photos') as $photo) {
                $path = $photo->store('dispositifs', 'public');
                $dispositif->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('user.dispositifs.index')->with('success','Dispositif créé avec succès.');
    }

    public function edit(Dispositif $dispositif)
    {
        abort_if($dispositif->user_id !== Auth::id(), 403);

        $categories = Categorie::all();
        $types = TypesDispositif::with('params')->get();
        $dispositif->load('params');

        return view('user.dispositifs.edit', compact('dispositif','categories','types'));
    }

    public function update(Request $request, Dispositif $dispositif)
    {
        abort_if($dispositif->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'types_dispositif_id'    => 'required|exists:types_dispositifs,id',
            'numero_immatriculation' => 'nullable|string',
            'designation'            => 'required|string',
            'description'            => 'nullable|string',
            'statut'                 => 'required|in:Actif,Inactif,Suspendu',
            'etat'                   => 'required|in:Neuf,Bon,Révisé',
            'photos.*'               => 'image|max:2048',
            'params'                 => 'nullable|array',
            'params.*'               => 'nullable|string',
        ]); 
        
        $dispositif->params()->delete();

        if ($request->filled('params')) {
            foreach ($request->params as $paramId => $value) {

                if (!is_numeric($paramId)) {
                    continue; // Ignore toute clé invalide
                }
                
                if (!empty($value)) {
                    $dispositif->params()->create([
                        'type_dispositif_param_id' => $paramId,
                        'value' => $value
                    ]);
                }
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('dispositifs', 'public');
                $dispositif->photos()->create([
                    'path' => $path,
                    'is_cover' => false,
                ]);
            }
        }

        return redirect()->route('user.dispositifs.index')->with('success', 'Dispositif mis à jour avec succès');
    }

    public function show(Dispositif $dispositif)
    {
        abort_if($dispositif->user_id !== auth()->id(), 403);

        $dispositif->load([
            'type_dispositif.categorie',
            'photos',
            'params.typeParam'
        ]);

        return view('user.dispositifs.show', compact('dispositif'));
    }

    public function destroy(Dispositif $dispositif)
    {
        abort_if($dispositif->user_id !== auth()->id(), 403);

        foreach ($dispositif->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $dispositif->delete();

        return redirect()->route('user.dispositifs.index')->with('success', 'Dispositif supprimé avec succès');
    }
}