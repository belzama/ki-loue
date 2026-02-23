<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysParam extends Model
{
    protected $fillable = [
        'code','value','desc'
    ];
}
