<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;

class DispositifParam extends Model
{
    protected $fillable = [
        'dispositif_id',
        'name',
        'value'
    ];

    public function dispositif() { 
        
        return $this->belongsTo(Dispositif::class); 
    
    }
    
    public function typeParam()
    {
        return $this->belongsTo(
            TypeDispositifParam::class,
            'name',
            'name'
        );
    }
    
}
