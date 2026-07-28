<?php

namespace App\Services;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;

class WhatsappNotificationService
{
    public function __construct(protected InfobipService $infobip)
    {
    }

    /**
     * Envoie une notification. L'email part TOUJOURS.
     * WhatsApp est envoyé en supplément si l'utilisateur est éligible et solvable.
     *
     * @param callable $emailCallback  Reçoit un statut : 'sent', 'insufficient_balance', 'not_opted_in', 'failed'
     */
    public function notify(User $user, string $templateKey, array $placeholders, callable $emailCallback): void
    {
        $whatsappStatus = $this->attemptWhatsapp($user, $templateKey, $placeholders);

        // 📧 Email systématique, avec le statut WhatsApp pour adapter le contenu si besoin
        $emailCallback($whatsappStatus);
    }

    protected function attemptWhatsapp(User $user, string $templateKey, array $placeholders): string
    {
        $template = config("services.infobip.whatsapp_templates.{$templateKey}");

        if (!$template) {
            Log::warning("Template WhatsApp inconnu pour notification : {$templateKey}");
            return 'failed';
        }

        $eligible = $user->whatsapp_notifications_opt_in
            && !empty($user->whatsapp)
            && $user->whatsapp_verified_at !== null;

        if (!$eligible) {
            return 'not_opted_in';
        }

        $cout = $template['cout'] ?? 0;

        if ($cout > 0) {
            try {
                TransactionService::execute(
                    user: $user,
                    montant: $cout,
                    type: 'retrait',
                    categorie: 'notification',
                    description: "Notification WhatsApp : {$templateKey}",
                );
            } catch (\Exception $e) {
                Log::info("WhatsApp annulé (solde insuffisant) user {$user->id} : {$e->getMessage()}");
                return 'insufficient_balance';
            }
        }

        $sent = $this->infobip->sendWhatsappTemplate($user->whatsapp, $templateKey, $placeholders);

        if (!$sent && $cout > 0) {
            TransactionService::execute(
                user: $user,
                montant: $cout,
                type: 'depot',
                categorie: 'remboursement',
                description: "Remboursement échec envoi WhatsApp : {$templateKey}",
            );
        }

        return $sent ? 'sent' : 'failed';
    }
}