<?php
// app/Http/Resources/PublicationResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'designation'   => $this->dispositif->designation ?? null,
            'active'        => (bool) $this->active,
            'date_debut'    => $this->created_at?->format('Y-m-d'),
            'date_fin'      => $this->date_fin,
            'devise'        => $this->devise?->code ?? $this->devise?->nom,
            'localisation'  => [
                'departement' => $this->departement?->nom,
                'region'      => $this->departement?->region?->nom,
                'pays'        => $this->departement?->region?->pays?->nom,
            ],
            'dispositif' => [
                'id'        => $this->dispositif->id,
                'designation' => $this->dispositif->designation,
                'type'      => $this->dispositif->type_dispositif->nom ?? null,
                'categorie' => $this->dispositif->type_dispositif->categorie->nom ?? null,
            ],
        ];
    }
}