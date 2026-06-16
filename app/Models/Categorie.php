<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TypesDispositif;

class Categorie extends Model
{
    protected $fillable = ['nom', 'image_link'];
    
    public function types_dispositifs()
    {
        return $this->hasMany(TypesDispositif::class);
    }
}
