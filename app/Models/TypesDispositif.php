<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;
use App\Models\TypeDispositifParam;
use App\Models\Categorie;

class TypesDispositif extends Model
{
    protected $fillable = ['categorie_id','nom','tarif_min','tarif_max','nb_max_photo'];
    
    public function dispositifs()
    {
        return $this->hasMany(Dispositif::class);
    }
    public function params()
    {
        return $this->hasMany(TypeDispositifParam::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
