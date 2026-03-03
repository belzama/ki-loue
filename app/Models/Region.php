<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;
use App\Models\Ville;

class Region extends Model
{
    
    protected $fillable = ['pays_id','nom'];

    public function pays() { return $this->belongsTo(Pays::class); }
    public function villes() { return $this->hasMany(Ville::class); }
}
