<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Devise;
use App\Models\Continent;
use App\Models\Pays;
use App\Models\Tarif;
use App\Models\ModePaiement;

class PaysController extends Controller
{
    /**
     * Affiche tous les pays (pour filtre ou API)
     */
    public function index()
    {
        $pays_list = Pays::with(['continent', 'devise'])->orderBy('nom')->get();
        return view('admin.pays.index', compact('pays_list'));
    }

    /**
     * Affiche un pays précis
     */
    public function show(Pays $pays)
    {
        return view('admin.pays.show', compact('pays'));
    }

    /**
     * Changer le pays courant (stocké en session)
     */
    public function change(Pays $pays)
    {
        session(['pays' => $pays]);
        return back();
    }

    /**
     * CRUD : create / store / edit / update / destroy
     * facultatif selon besoins
     */
    public function create()
    {
        $continents = Continent::all();
        $devises = Devise::all();

        return view('admin.pays.create',compact('continents', 'devises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'continent_id' => 'required|exists:continents,id',
            'devise_id' => 'required|exists:devises,id',
            'code' => 'required|max:5',
            'indicatif' => 'required|max:10',
            'nom' => 'required|string|max:150',
            'libelle_division' => 'required|string|max:150',
            'libelle_sous_division' => 'required|string|max:150',
            'nationalite' => 'required|string|max:150',
            'langue_officielle' => 'required|string|max:150',
            'nb_jour_min_pub' => 'required|integer|min:1',
            'bonus_sponsor' => 'required|numeric|min:0',
            'taux_sponsor_new' => 'required|numeric|min:0',
            'drapeau' => 'nullable|string|max:255',

            'tarifs.*.designation' => 'required|string|max:150',
            'tarifs.*.tranche_debut' => 'required|integer|min:1',
            'tarifs.*.tranche_fin' => 'required|integer|min:1',
            'tarifs.*.tranche_valeur' => 'required|numeric|min:0',

            'mode_paiements.*.designation' => 'required|string|max:150',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
        ]);

        DB::transaction(function() use($request) {
            $pays = Pays::create($request->only([
                'continent_id','devise_id','code','indicatif','nom',
                'nationalite','langue_officielle','nb_jour_min_pub',
                'bonus_sponsor','taux_sponsor_new','drapeau'
            ]));

            if ($request->has('tarifs')) {
                foreach($request->tarifs as $tarif){
                    if(empty($tarif['designation'])) continue;
                    $pays->tarifs()->create($tarif);
                }
            }

            if ($request->has('mode_paiements')) {
                foreach($request->mode_paiements as $mode){
                    if(empty($mode['designation']) || empty($mode['type'])) continue;
                    $pays->modePaiements()->create($mode);
                }
            }
        });

        return redirect()->route('admin.pays.index')->with('success','Pays ajouté.');
    }

    public function edit(Pays $pays)
    {
        $continents = Continent::all();
        $devises = Devise::all();
        return view('admin.pays.edit', compact('continents', 'pays', 'devises'));
    }

    public function update(Request $request, Pays $pays)
    {
        $request->validate([
            'continent_id' => 'required|exists:continents,id',
            'devise_id' => 'required|exists:devises,id',
            'code' => 'required|max:5',
            'indicatif' => 'required|max:10',
            'nom' => 'required|string|max:150',
            'libelle_division' => 'required|string|max:150',
            'libelle_sous_division' => 'required|string|max:150',
            'nationalite' => 'required|string|max:150',
            'langue_officielle' => 'required|string|max:150',
            'nb_jour_min_pub' => 'required|integer|min:1',
            'bonus_sponsor' => 'required|numeric|min:0',
            'taux_sponsor_new' => 'required|numeric|min:0',
            'drapeau' => 'nullable|string|max:255',

            'tarifs.*.designation' => 'required|string|max:150',
            'tarifs.*.tranche_debut' => 'required|integer|min:1',
            'tarifs.*.tranche_fin' => 'required|integer|min:1',
            'tarifs.*.tranche_valeur' => 'required|numeric|min:0',

            'mode_paiements.*.designation' => 'required|string|max:150',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
        ]);

        DB::transaction(function() use($request, $pays) {
            $pays->update($request->only([
                'continent_id','devise_id','code','indicatif','nom',
                'nationalite','langue_officielle','nb_jour_min_pub',
                'bonus_sponsor','taux_sponsor_new','drapeau'
            ]));

            $pays->tarifs()->delete();
            if($request->has('tarifs')){
                foreach($request->tarifs as $tarif){
                    if(empty($tarif['designation'])) continue;
                    $pays->tarifs()->create($tarif);
                }
            }

            $pays->modePaiements()->delete();
            if($request->has('mode_paiements')){
                foreach($request->mode_paiements as $mode){
                    if(empty($mode['designation']) || empty($mode['type'])) continue;
                    $pays->modePaiements()->create($mode);
                }
            }
        });

        return redirect()->route('admin.pays.index')->with('success','Pays mis à jour.');
    }

    public function destroy(Pays $pays)
    {
        $pays->delete();
        return redirect()->route('admin.pays.index')->with('success', 'Pays supprimé.');
    }

    public function getTarifs($pays_id)
    {
        $tarifs = Tarif::where('pays_id', $pays_id)
            ->orderBy('tranche_debut')
            ->get();

        return response()->json($tarifs);
    }

    
}
