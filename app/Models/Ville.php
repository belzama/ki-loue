<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Region;

class Ville extends Model
{
    protected $fillable = ['region_id','nom'];

    public function region() { return $this->belongsTo(Region::class); }
}
