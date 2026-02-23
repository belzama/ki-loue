<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Devise;
use App\Models\Continent;
use App\Models\Pays;
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
            'code' => 'required|unique:pays,code|max:5',
            'indicatif' => 'required|max:10',
            'nom' => 'required|string|max:255',
            'nationalite' => 'required|string|max:255',
            'langue_officielle' => 'required|string|max:255',
            'taux_commission' => 'required|numeric|min:0',
            'bonus_sponsor' => 'required|numeric|min:0',
            'taux_sponsor_new' => 'required|numeric|min:0',
            'drapeau' => 'nullable|string|max:255',

            'mode_paiements.*.designation' => 'required|string|max:255',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $pays = Pays::create($request->only([
                'continent_id',
                'devise_id',
                'code',
                'indicatif',
                'nom',
                'nationalite',
                'langue_officielle',
                'taux_commission',
                'bonus_sponsor',
                'taux_sponsor_new',
                'drapeau'
            ]));

            if ($request->has('mode_paiements')) {

                foreach ($request->mode_paiements as $mode) {

                    $pays->modePaiements()->create([
                        'designation' => $mode['designation'],
                        'api_url' => $mode['api_url'] ?? null,
                        'numero_compte' => $mode['numero_compte'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays ajouté avec ses modes de paiement.');
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
            'code' => "required|unique:pays,code,{$pays->id}|max:5",
            'indicatif' => 'required|max:10',
            'nom' => 'required|string|max:255',
            'nationalite' => 'required|string|max:255',
            'langue_officielle' => 'required|string|max:255',
            'taux_commission' => 'required|numeric|min:0',
            'bonus_sponsor' => 'required|numeric|min:0',
            'taux_sponsor_new' => 'required|numeric|min:0',
            'drapeau' => 'nullable|string|max:255',

            'mode_paiements.*.designation' => 'required|string|max:255',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $pays) {

            $pays->update($request->only([
                'continent_id',
                'devise_id',
                'code',
                'indicatif',
                'nom',
                'nationalite',
                'langue_officielle',
                'taux_commission',
                'bonus_sponsor',
                'taux_sponsor_new',
                'drapeau'
            ]));

            // suppression anciens modes
            $pays->modePaiements()->delete();

            // recréation
            if ($request->has('mode_paiements')) {

                foreach ($request->mode_paiements as $mode) {

                    $pays->modePaiements()->create([
                        'designation' => $mode['designation'],
                        'type' => $mode['type'],
                        'api_url' => $mode['api_url'] ?? null,
                        'numero_compte' => $mode['numero_compte'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.pays.index')
            ->with('success', 'Pays modifié avec ses modes de paiement.');
    }

    public function destroy(Pays $pays)
    {
        $pays->delete();
        return redirect()->route('admin.pays.index')->with('success', 'Pays supprimé.');
    }
}
