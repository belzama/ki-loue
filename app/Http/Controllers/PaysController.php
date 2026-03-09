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
        info("1. Début de la méthode update pour le pays : " . $pays->id);
        try {
            // Validation principale
            $request->validate([
                'continent_id' => 'required|exists:continents,id',
                'devise_id' => 'required|exists:devises,id',
                'code' => "required|max:5",
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
            info("2. Validation passée avec succès");
            //return "Le formulaire n'est pas valide";
            DB::transaction(function () use ($request, $pays) {
                info("3. Entrée dans la transaction");
                // Update des infos principales
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
                info("4. Mise à jour des infos principales terminée");

                // Suppression des anciens modes de paiement
                $pays->modePaiements()->delete();
                info("5. Anciens modes de paiement supprimés");
                // Création des nouveaux modes
                if ($request->has('mode_paiements')) {
                    foreach ($request->mode_paiements as $index => $mode) {

                        // Ignorer les lignes vides
                        if (empty($mode['designation']) || empty($mode['type'])) {
                            continue;
                        }

                        $pays->modePaiements()->create([
                            'designation' => $mode['designation'],
                            'type' => $mode['type'],
                            'api_url' => $mode['api_url'] ?? null,
                            'numero_compte' => $mode['numero_compte'] ?? null,
                        ]);
                        info("7. Mode de paiement ajouté pour l'index : " . $index);
                    }
                }
            });

            info("8. Transaction validée (Commit)");
            return redirect()->route('admin.pays.index')
                ->with('success', 'Pays modifié avec succès avec ses modes de paiement.');
        } catch (\Exception $e) {// En cas d'erreur, on logue le message précis et la ligne
            info("ERREUR DÉTECTÉE : " . $e->getMessage());
            info("Fichier : " . $e->getFile() . " à la ligne " . $e->getLine());
            //Log::error("Erreur lors de l'update pays : " . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
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
