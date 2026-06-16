<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodicite extends Model
{
    protected $fillable = [
        'libelle',
        'couleur',
        'nb_jour',
        'qte',
        'taux_remise'
    ];
}
