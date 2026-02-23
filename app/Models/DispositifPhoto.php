<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositifPhoto extends Model
{
    protected $fillable = ['dispositif_id','path','is_cover'];

    public function dispositif()
    {
        return $this->belongsTo(Dispositif::class);
    }
}
