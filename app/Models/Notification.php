<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'read',
        'send_email',
        'send_whatsapp',
        'send_email_address',
        'send_whatsapp_number',
        'send_email_date',
        'send_whatsapp_date',
    ];

    // Casts pour transformer automatiquement les colonnes en types natifs PHP
    protected $casts = [
        'read' => 'boolean',
        'send_email' => 'boolean',
        'send_whatsapp' => 'boolean',
        'send_email_address' => 'string',
        'send_whatsapp_number' => 'string',
        'send_email_date' => 'datetime',
        'send_whatsapp_date' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
