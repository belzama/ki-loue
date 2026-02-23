<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TypeDispositif;

class TypeDispositifParam extends Model
{
    protected $fillable = [
        'types_dispositif_id',
        'name',
        'label',
        'value_type',
        'list_values',
        'numeric_value_unit',
        'required',
    ];

    public function type_dispositif() { 
        return $this->belongsTo(TypesDispositif::class, 'types_dispositif_id'); 
    }   
    
     // helper pratique
    public function getListArrayAttribute()
    {
        return $this->list_values
            ? explode(',', $this->list_values)
            : [];
    }
}
