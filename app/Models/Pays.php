<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Continent;
use App\Models\Devise;
use App\Models\Region;
use App\Models\ModePaiement;

class Pays extends Model
{
    protected $fillable = [
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
    ];

    public function continent() { return $this->belongsTo(Continent::class); }
    public function devise() { return $this->belongsTo(Devise::class); }
    public function regions() { return $this->hasMany(Region::class); }
    public function modePaiements() { return $this->hasMany(ModePaiement::class); }
}
