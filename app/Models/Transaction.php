<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    protected $fillable = [
        'created_at',
        'user_id',
        'montant',
        'type',
        'categorie',
        'reference',
        'statut',
        'solde_apres',
        'description',
        'date_annulation',
        'motif_annulation',
        'created_by'
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }   
}
