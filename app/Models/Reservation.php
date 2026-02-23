<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Publication;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Les colonnes pouvant être assignées en masse
     */
    protected $fillable = [
        'publication_id',
        'user_id',
        'date_reservation',
        'date_demandee',
        'duree_demandee',
        'nom_prenom',
        'email',
        'telephone',
        'message',
        'date_accordee',
        'duree_accordee',
        'motif_approbation',
        'statut',
    ];

    /**
     * Relation avec la publication
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Relation avec l'utilisateur (optionnelle)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si la réservation est accordée
     */
    public function isAccordee(): bool
    {
        return $this->statut === 'Accordée';
    }

    /**
     * Vérifie si la réservation est demandée
     */
    public function isDemandee(): bool
    {
        return $this->statut === 'Demandée';
    }

    /**
     * Vérifie si la réservation est rejetée
     */
    public function isRejetee(): bool
    {
        return $this->statut === 'Rejetée';
    }
}
