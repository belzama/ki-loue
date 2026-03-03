<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispositif;

class DispositifParam extends Model
{
    protected $fillable = [
        'dispositif_id',
        'type_dispositif_param_id',
        'value'
    ];

    public function dispositif() { 
        
        return $this->belongsTo(Dispositif::class); 
    
    }
    
    public function typeParam()
    {
        return $this->belongsTo(TypeDispositifParam::class, 'type_dispositif_param_id');
    }
    
}
