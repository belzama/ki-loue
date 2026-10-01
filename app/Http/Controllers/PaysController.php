<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $pays_list = Pays::with(['continent', 'devise'])
            ->orderBy('nom')
            ->paginate(10);
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
     */
    public function create()
    {
        $continents = Continent::all();
        $devises = Devise::all();

        return view('admin.pays.create', compact('continents', 'devises'));
    }

    /**
     * Règle de validation du logo, partagée entre store() et update().
     * Ignore silencieusement les inputs file vides/invalides (comportement normal
     * pour une ligne dont le logo existant est conservé sans re-upload).
     */
    protected function logoValidationRule()
    {
        return function ($attribute, $value, $fail) {
            if (!$value instanceof \Illuminate\Http\UploadedFile || !$value->isValid()) {
                return;
            }

            $validator = \Illuminate\Support\Facades\Validator::make(
                ['logo' => $value],
                ['logo' => 'image|max:2048']
            );

            if ($validator->fails()) {
                $fail('Le logo doit être une image valide (jpg, png...) de 2 Mo maximum.');
            }
        };
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

            // Avec le système modal + liste, ces tableaux ne sont plus garantis
            // présents par défaut : on force au moins une entrée.
            'tarifs' => 'required|array|min:1',
            'tarifs.*.designation' => 'required|string|max:150',
            'tarifs.*.tranche_debut' => 'required|integer|min:1',
            'tarifs.*.tranche_fin' => 'required|integer|min:1',
            'tarifs.*.tranche_valeur' => 'required|numeric|min:0',

            'mode_paiements' => 'required|array|min:1',
            'mode_paiements.*.designation' => 'required|string|max:150',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
            'mode_paiements.*.logo' => [$this->logoValidationRule()],
        ], [
            'tarifs.required' => 'Ajoutez au moins un tarif.',
            'mode_paiements.required' => 'Ajoutez au moins un mode de paiement.',
        ]);

        DB::transaction(function () use ($request) {
            $pays = Pays::create($request->only([
                'continent_id',
                'devise_id',
                'code',
                'indicatif',
                'nom',
                'libelle_division',
                'libelle_sous_division',
                'nationalite',
                'langue_officielle',
                'nb_jour_min_pub',
                'bonus_sponsor',
                'taux_sponsor_new',
                'drapeau'
            ]));

            foreach ($request->tarifs as $tarif) {
                if (empty($tarif['designation'])) continue;
                $pays->tarifs()->create($tarif);
            }

            foreach ($request->mode_paiements as $i => $mode) {
                if (empty($mode['designation']) || empty($mode['type'])) continue;

                $data = collect($mode)->except('logo')->toArray();

                if ($request->hasFile("mode_paiements.$i.logo")) {
                    $data['logo'] = $request->file("mode_paiements.$i.logo")
                        ->store('mode_paiements', 'public');
                }

                $pays->modePaiements()->create($data);
            }
        });

        return redirect()->route('admin.pays.index')->with('success', 'Pays ajouté.');
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

            'tarifs' => 'required|array|min:1',
            'tarifs.*.designation' => 'required|string|max:150',
            'tarifs.*.tranche_debut' => 'required|integer|min:1',
            'tarifs.*.tranche_fin' => 'required|integer|min:1',
            'tarifs.*.tranche_valeur' => 'required|numeric|min:0',

            'mode_paiements' => 'required|array|min:1',
            'mode_paiements.*.designation' => 'required|string|max:150',
            'mode_paiements.*.type' => 'required|string',
            'mode_paiements.*.api_url' => 'nullable|string',
            'mode_paiements.*.numero_compte' => 'nullable|string',
            'mode_paiements.*.logo' => [$this->logoValidationRule()],
            'mode_paiements.*.logo_existant' => 'nullable|string',
        ], [
            'tarifs.required' => 'Ajoutez au moins un tarif.',
            'mode_paiements.required' => 'Ajoutez au moins un mode de paiement.',
        ]);

        DB::transaction(function () use ($request, $pays) {

            // =========================================================
            // 1. Mise à jour des informations du pays
            // =========================================================
            $pays->update($request->only([
                'continent_id',
                'devise_id',
                'code',
                'indicatif',
                'nom',
                'libelle_division',
                'libelle_sous_division',
                'nationalite',
                'langue_officielle',
                'nb_jour_min_pub',
                'bonus_sponsor',
                'taux_sponsor_new',
                'drapeau'
            ]));


            // =========================================================
            // 2. Mise à jour des tarifs
            // =========================================================
            $pays->tarifs()->delete();

            foreach ($request->tarifs as $tarif) {

                if (empty($tarif['designation'])) {
                    continue;
                }

                $pays->tarifs()->create($tarif);
            }


            // =========================================================
            // 3. Mémoriser les anciens logos
            // =========================================================
            $anciensLogos = $pays->modePaiements()
                ->whereNotNull('logo')
                ->pluck('logo')
                ->filter()
                ->values()
                ->toArray();


            // =========================================================
            // 4. Supprimer les anciens modes de paiement
            // =========================================================
            $pays->modePaiements()->delete();


            // =========================================================
            // 5. Recréer les modes de paiement
            // =========================================================
            $logosConserves = [];

            foreach ($request->mode_paiements as $i => $mode) {

                if (empty($mode['designation']) || empty($mode['type'])) {
                    continue;
                }

                // On ne conserve PAS logo / logo_existant dans le
                // tableau avant de traiter le fichier.
                $data = collect($mode)
                    ->except(['logo', 'logo_existant'])
                    ->toArray();


                // -----------------------------------------------------
                // Nouveau logo envoyé
                // -----------------------------------------------------
                if ($request->hasFile("mode_paiements.$i.logo")) {

                    $data['logo'] = $request
                        ->file("mode_paiements.$i.logo")
                        ->store('mode_paiements', 'public');

                }

                // -----------------------------------------------------
                // Aucun nouveau logo :
                // conservation de l'ancien logo
                // -----------------------------------------------------
                elseif (!empty($mode['logo_existant'])) {

                    $data['logo'] = $mode['logo_existant'];

                    $logosConserves[] = $mode['logo_existant'];
                }


                // Création du nouveau mode
                $pays->modePaiements()->create($data);
            }


            // =========================================================
            // 6. Supprimer uniquement les anciens logos abandonnés
            // =========================================================
            foreach ($anciensLogos as $ancienLogo) {

                if (!in_array($ancienLogo, $logosConserves, true)) {

                    Storage::disk('public')->delete($ancienLogo);
                }
            }
        });

        return redirect()->route('admin.pays.index')->with('success', 'Pays mis à jour.');
    }

    public function destroy(Pays $pays)
    {
        // Supprime aussi les logos physiques des modes de paiement associés
        foreach ($pays->modePaiements as $mode) {
            if ($mode->logo) {
                Storage::disk('public')->delete($mode->logo);
            }
        }

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