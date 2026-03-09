<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;

class Tarif extends Model
{
    
    protected $fillable = [
        'pays_id',
        'designation',
        'tranche_debut',
        'tranche_fin',
        'tranche_valeur'
    ];

    public function pays() { 
        return $this->belongsTo(Pays::class); 
    }  
}
