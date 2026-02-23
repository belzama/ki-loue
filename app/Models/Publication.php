<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;
use App\Models\Ville;
use App\Models\Devise;
use App\Models\Reservation;

class Publication extends Model
{
    protected $fillable = [
        'dispositif_id','ville_id','devise_id','tarif_location','prix_publication','bonus_accorde','cout_publication','date_debut','date_fin','active'
    ];

    public function dispositif() { return $this->belongsTo(Dispositif::class); }
    public function ville() { return $this->belongsTo(Ville::class); }
    public function devise() { return $this->belongsTo(Devise::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }
}
