<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;

class Continent extends Model
{
    protected $fillable = ['id','nom'];
    public function pays()
    {
        return $this->hasMany(Pays::class);
    }
}
