<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;

class ModePaiement extends Model
{
    protected $fillable = [
        'pays_id',
        'designation',
        'type',
        'api_url',
        'numero_compte'
    ];

    public function pays() { 
        
        return $this->belongsTo(Pays::class); 
    
    }
}
