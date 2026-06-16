<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;
use App\Models\Periodicite;

class Abonnement extends Model
{
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    protected $fillable = [
        'dispositif_id',
        'periodicite_id',
        'date_debut',
        'date_fin',
        'montant',
        'actif'
    ];

    public function dispositif() { return $this->belongsTo(Dispositif::class); }
    public function periodicite() { return $this->belongsTo(Periodicite::class); }
}
