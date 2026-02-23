<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;

class Ville extends Model
{
    protected $fillable = ['pays_id','nom'];

    public function pays() { return $this->belongsTo(Pays::class); }
}
