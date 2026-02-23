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
        $this->middleware(['auth','role:Admin,User']);
    }

    /**
     * Liste des dispositifs du user
     */
    public function index(Request $request)
    {
        $categories = Categorie::orderBy('nom')->get();

        // ⚠️ TOUS les types, sans filtre
        $types = TypesDispositif::orderBy('nom')->get();

        $dispositifs = Dispositif::with(['type_dispositif.categorie', 'cover'])
            ->where('user_id', auth()->id())
            ->when($request->categorie_id, function ($q) use ($request) {
                $q->whereHas('type_dispositif', function ($q2) use ($request) {
                    $q2->where('categorie_id', $request->categorie_id);
                });
            })
            ->when($request->types_dispositif_id, function ($q) use ($request) {
                $q->where('types_dispositif_id', $request->types_dispositif_id);
            })
            ->paginate(10);

        return view('user.dispositifs.index', compact(
            'categories',
            'types',
            'dispositifs'
        ));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $types = TypesDispositif::with('params')->get();
        return view('user.dispositifs.create', compact('types'));
    }

    /**
     * Enregistrer nouveau dispositif
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'types_dispositif_id' => 'required|exists:types_dispositifs,id',
            'numero_immatriculation' => 'nullable|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'statut' => 'required|in:Actif,Inactif,Suspendu',
            'photos.*' => 'image|max:2048',
        ]);

        $data['user_id'] = Auth::id();

        $dispositif = Dispositif::create($data);

        /* ===========================
        ENREGISTREMENT PARAMETRES
        =========================== */

        if ($request->has('params')) {

            foreach ($request->params as $name => $value) {

                if ($value !== null && $value !== '') {

                    $dispositif->params()->create([
                        'name' => $name,
                        'value' => $value
                    ]);
                }
            }
        }

        // Gestion des photos
        if ($request->hasFile('photos')) {
            foreach($request->file('photos') as $photo) {
                $path = $photo->store('dispositifs', 'public');
                $dispositif->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('user.dispositifs.index')
                         ->with('success','Dispositif créé avec succès.');
    }

    /**
     * Formulaire édition
     */
    public function edit(Dispositif $dispositif)
    {
        // Vérifie que l'utilisateur possède ce dispositif
        abort_if($dispositif->user_id !== Auth::id(), 403);

        $types = TypesDispositif::with('params')->get();

        $dispositif->load('params');

        return view('user.dispositifs.edit', compact('dispositif','types'));
    }

    /**
     * Mettre à jour dispositif
     */
    public function update(Request $request, Dispositif $dispositif)
    {
        if ($dispositif->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'types_dispositif_id' => 'required|exists:types_dispositifs,id',
            'numero_immatriculation' => 'nullable|string',
            'designation' => 'required|string',
            'description' => 'nullable|string',
            'statut' => 'required|in:Actif,Inactif,Suspendu',
            'photos.*' => 'image|max:2048',
        ]); 
        
        // 🔹 Mise à jour des champs simples
        $dispositif->update([
            'types_dispositif_id' => $data['types_dispositif_id'],
            'numero_immatriculation' => $data['numero_immatriculation'] ?? null,
            'designation' => $data['designation'],
            'description' => $data['description'] ?? null,
            'statut' => $data['statut'],
        ]);

        //Supprimer et enregistrer les paramètres
        $dispositif->params()->delete();

        if ($request->has('params')) {

            foreach ($request->params as $name => $value) {

                if ($value !== null && $value !== '') {

                    $dispositif->params()->create([
                        'name' => $name,
                        'value' => $value
                    ]);
                }
            }
        }
        // 🔹 AJOUT DE NOUVELLES PHOTOS (sans toucher aux anciennes)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('dispositifs', 'public');
                
                $isFirst = $dispositif->photos()->count() === 0;
                
                $dispositif->photos()->create([
                    'path' => $path,
                    'is_cover' => false,
                ]);
            }
        }

        return redirect()
            ->route('user.dispositifs.index')
            ->with('success', 'Dispositif mis à jour avec succès');
    }

    public function show(Dispositif $dispositif)
    {
        // sécurité : empêcher d’accéder aux dispositifs des autres
        abort_if($dispositif->user_id !== auth()->id(), 403);

        $dispositif->load([
            'type_dispositif.categorie',
            'photos',
            'params',
            'params.typeParam'
        ]);

        return view('user.dispositifs.show', compact('dispositif'));
    }

    public function destroy(Dispositif $dispositif)
    {
        // sécurité : empêcher la suppression des autres utilisateurs
        abort_if($dispositif->user_id !== auth()->id(), 403);

        // supprimer les photos physiques
        foreach ($dispositif->photos as $photo) {
            \Storage::disk('public')->delete($photo->path);
        }

        // suppression du dispositif (cascade photos)
        $dispositif->delete();

        return redirect()
            ->route('user.dispositifs.index')
            ->with('success', 'Dispositif supprimé avec succès');
    }
}
