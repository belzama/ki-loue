<?php
// app/Http/Resources/PublicationResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PublicationResource extends JsonResource
{
    public function toArray($request)
    {
        $dispositif = $this->dispositif;
        $departement = $this->departement;

        return [
            'id'     => $this->id,
            'active' => (bool) $this->active,

            // Tarification
            'tarif_location'   => (float) $this->tarif_location,
            'devise' => $this->whenLoaded('devise', fn () => [
                'id'   => $this->devise->id,
                'code' => $this->devise->code ?? null,
                'nom'  => $this->devise->nom ?? null,
            ]),

            // Localisation
            'localisation' => [
                'ville'       => $this->ville,
                'departement' => $departement?->nom,
                'region'      => $departement?->region?->nom,
                'pays'        => $departement?->region?->pays?->nom,
            ],

            // Dispositif
            'dispositif' => $this->whenLoaded('dispositif', fn () => [
                'id'                     => $dispositif->id,
                'designation'            => $dispositif->designation,
                'marque'                 => $dispositif->marque,
                'modele'                 => $dispositif->modele,

                'type'      => $dispositif->typeDispositif?->nom,
                'categorie' => $dispositif->typeDispositif?->categorie?->nom,

                /*'proprietaire' => $dispositif->relationLoaded('user') && $dispositif->user ? [
                    'id'   => $dispositif->user->id,
                    'name' => $dispositif->user->name,
                ] : null,*/

                'photos' => $dispositif->photos->map(fn ($photo) => [
                    'id'       => $photo->id,
                    'url'      => Storage::url($photo->path),
                    'is_cover' => (bool) $photo->is_cover,
                ])->values(),

                'cover' => optional($dispositif->photos->firstWhere('is_cover', true))->path
                    ? Storage::url($dispositif->photos->firstWhere('is_cover', true)->path)
                    : null,

                'caracteristiques' => $dispositif->params->map(function ($param) {
                    $def = $param->typeParam;

                    return [
                        'label' => $def->label,
                        'unit'  => $def->numeric_value_unit,
                        'value' => $this->castParamValue($param->value, $def->value_type),
                    ];
                })->values(),
            ]),
        ];
    }

    private function castParamValue($value, string $type)
    {
        return match ($type) {
            'int'     => (int) $value,
            'decimal' => (float) $value,
            default   => $value, // string, date, datetime
        };
    }
}