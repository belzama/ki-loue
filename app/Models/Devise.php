<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;
use App\Models\Publication;

class Devise extends Model
{
    protected $fillable = ['id','code','symbol','libelle'];
    public function pays(){return $this->hasMany(Pays::class);}

    public function publications(){return $this->hasMany(Publication::class);}
}
