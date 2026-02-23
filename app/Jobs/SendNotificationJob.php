<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Notification $notification) {}

    public function handle(): void
    {
        // Logique Email
        if ($this->notification->send_email && !$this->notification->send_email_date) {
            // Mail::to($this->notification->send_email_address)->send(...);
            $this->notification->update(['send_email_date' => now()]);
        }

        // Logique WhatsApp
        if ($this->notification->send_whatsapp && !$this->notification->send_whatsapp_date) {
            // Appel API WhatsApp...
            $this->notification->update(['send_whatsapp_date' => now()]);
        }
    }
}