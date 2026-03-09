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
            'marque'            => 'required|string',
            'modele'            => 'required|string',
            'description'            => 'nullable|string',
            'etat'                   => 'required|in:Neuf,Bon,Révisé',
            'photos'                 => 'required|array|min:1',        // <-- au moins 1 photo
            'photos.*'               => 'image|mimes:jpg,jpeg,png|max:2048',
            'params'                 => 'nullable|array',
            'params.*'               => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $data['designation'] = $data['marque']. ' '.$data['modele'];
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

        $request['designation'] = $request['marque'].' '.$request['modele'];

        $data = $request->validate([
            'types_dispositif_id' => 'required|exists:types_dispositifs,id',
            'numero_immatriculation' => 'nullable|string',
            'marque' => 'required|string',
            'modele' => 'required|string',
            'description' => 'nullable|string',
            'etat' => 'required|in:Neuf,Bon,Révisé',
            'photos' => 'required|array|min:1',        // <-- au moins 1 photo
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'params' => 'nullable|array',
            'params.*' => 'nullable|string',
        ]);

        $dispositif->update($data);

        /*
        |---------------------------
        | PARAMETRES
        |---------------------------
        */

        $dispositif->params()->delete();

        if ($request->filled('params')) {

            foreach ($request->params as $paramId => $value) {

                if (!is_numeric($paramId)) continue;

                if (!empty($value)) {

                    $dispositif->params()->create([
                        'type_dispositif_param_id' => $paramId,
                        'value' => $value
                    ]);
                }
            }
        }

        /*
        |---------------------------
        | PHOTOS
        |---------------------------
        */

        $existingPhotos = $request->input('existing_photos', []);
        $uploadedPhotos = $request->file('photos', []);

        $currentPhotos = $dispositif->photos()->get()->keyBy('id');

        // --- SUPPRESSION DES PHOTOS SUPPRIMÉES
        $keepIds = array_filter($existingPhotos);
        $photosToDelete = $dispositif->photos()->whereNotIn('id', $keepIds)->get();
        foreach($photosToDelete as $photo){
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }
        
        // --- UPLOAD / REMPLACEMENT
        foreach ($uploadedPhotos as $index => $photoFile) {
            if (!$photoFile) continue;

            $oldPhotoId = $existingPhotos[$index] ?? null;

            if ($oldPhotoId && isset($currentPhotos[$oldPhotoId])) {
                // remplacer photo existante
                $oldPhoto = $currentPhotos[$oldPhotoId];
                Storage::disk('public')->delete($oldPhoto->path);
                $path = $photoFile->store('dispositifs','public');
                $oldPhoto->update(['path' => $path]);
            } else {
                // nouvelle photo
                $path = $photoFile->store('dispositifs','public');
                $dispositif->photos()->create([
                    'path' => $path,
                    'is_cover' => $dispositif->photos()->count() == 0
                ]);
            }
        }

        return redirect()
            ->route('user.dispositifs.index')
            ->with('success','Dispositif mis à jour avec succès');
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

    public function getTarifMin(Dispositif $dispositif) 
    {
        return response()->json([
            'tarif_min' => $dispositif->type_dispositif->tarif_min
        ]);
    }
}