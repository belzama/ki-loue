<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TypesDispositif;
use App\Models\User;
use App\Models\Publication;
use App\Models\DispositifParam;
use App\Models\DispositifPhoto;

class Dispositif extends Model
{
    protected $fillable = [
        'types_dispositif_id',
        'user_id',
        'numero_immatriculation',
        'marque',
        'modele',
        'designation',
        'description',
        'etat'];

    public function type_dispositif() {
        return $this->belongsTo(TypesDispositif::class, 'types_dispositif_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function params() {
        return $this->hasMany(DispositifParam::class);
    }

    public function photos() {
        return $this->hasMany(DispositifPhoto::class);
    }
    public function cover() {
        return $this->hasOne(DispositifPhoto::class)
            ->where('is_cover', true);
    }

    public function getMainPhotoAttribute() {
        return $this->cover ?: $this->photos->first();
    }

    public function publications() {
        return $this->hasMany(Publication::class);
    }

    public function publicationEncours()
    {
        return $this->hasOne(Publication::class)
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->where('active', '=', 1)
            ->latest(); // En cas de chevauchement, prend la plus récente
    }
    public function reservations() {
        return $this->hasManyThrough(Reservation::class, Publication::class);
    }
}
