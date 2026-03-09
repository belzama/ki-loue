<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Dispositif;
use App\Models\Pays;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\TransactionService;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const ADMIN = 'Admin';
    const GUEST = 'User';

    protected $fillable = [
        'code',
        'pays_id',
        'user_id',
        'type',
        'role',
        'nom',
        'prenom',
        'raison_sociale',
        'email',
        'telephone',
        'whatsapp',
        'password',
        'taux_commission',
        'taux_commission_sponsor'
    ];
    
    protected $guarded = [
        'solde_reel',
        'solde_bonus'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {

            // Générer le code seulement s'il est vide
            if (empty($user->code)) {

                do {
                    $code = 'USR-' . strtoupper(Str::random(6));
                } while (User::where('code', $code)->exists());

                $user->code = $code;
            }
        });
    }

    public function dispositifs()
    {
        return $this->hasMany(Dispositif::class);
    }    

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }
    
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'user_id');
    } 

    // Filleuls
    public function filleuls()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    
    public function reservations() 
    { 
        return $this->hasMany(Reservation::class); 
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }  
    
    public function appliquerBonusParrainage()
    {
        if (!$this->sponsor) {
            return;
        }

        DB::transaction(function () {
            $bonusSponsor = (float) $this->pays->bonus_sponsor ?: sys_param('SPONSOR_BONUS', 0);
            $percentNew   = (float) $this->pays->taux_sponsor_new ?: sys_param('SPONSOR_RATE_NEW', 0);

            if ($bonusSponsor <= 0) {
                return;
            }

            // 1️⃣ Bonus sponsor

            TransactionService::execute(
                $this->sponsor,
                $bonusSponsor,
                'Dépôt',
                'Bonus',
                'Bonus sponsor inscription'
            );
            //$this->sponsor->increment('solde_bonus', $bonusSponsor);

            // 2️⃣ Bonus nouvel inscrit (% du bonus sponsor)
            $bonusNew = ($bonusSponsor * $percentNew) / 100;

            if ($bonusNew > 0) {

                TransactionService::execute(
                    $this,
                    $bonusNew,
                    'Dépôt',
                    'Bonus',
                    'Bonus de bienvenue parrainage'
                );
                //$this->increment('solde_bonus', $bonusNew);
            }
        });
    }
}
