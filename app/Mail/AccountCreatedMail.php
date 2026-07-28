<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre compte a été créé')
            ->view('emails.account-created')
            ->with([
                'nom' => $this->user->nom,
                'prenom' => $this->user->prenom,
                'email' => $this->user->email,
                'password' => $this->password,
            ]);
    }
}