<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;
use App\Models\Departement;
use App\Models\Devise;
use App\Models\Reservation;

class Publication extends Model
{
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
    
    protected $fillable = [
        'dispositif_id','departement_id','devise_id','ville','tarif_location','prix_publication','bonus_accorde','cout_publication','date_debut','date_fin','active'
    ];

    public function dispositif() { return $this->belongsTo(Dispositif::class); }
    public function departement() { return $this->belongsTo(Departement::class); }
    public function devise() { return $this->belongsTo(Devise::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    // Dans App\Models\Publication.php

public function getIsExpiredAttribute()
{
    return $this->date_fin->isPast();
}

public function getIsActiveAttribute()
{
    // Une publication est active SEULEMENT si son statut est 1 ET qu'elle n'est pas expirée
    return $this->active == 1 && !$this->is_expired;
}
}
