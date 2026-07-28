<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Reservation;
use App\Models\User;
use App\Models\WhatsappInboundMessage;
use App\Services\InfobipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InfobipWebhookController extends Controller
{
    public function __construct(protected InfobipService $infobip)
    {
    }

    public function handleWhatsappInbound(Request $request)
    {
        foreach ($request->input('results', []) as $result) {
            $messageId = $result['messageId'] ?? null;
            $from = $result['from'] ?? null;
            $text = $result['message']['text'] ?? null;
            $type = $result['message']['type'] ?? null;

            if (!$messageId || !$from || !$text || $type !== 'TEXT') {
                continue; // ignore les callbacks non-texte (statuts de livraison, etc.)
            }

            // 🔁 Déduplication stricte : Infobip peut renvoyer le même webhook plusieurs fois
            if (WhatsappInboundMessage::where('message_id', $messageId)->exists()) {
                continue;
            }

            $this->processInboundMessage($messageId, $from, $text);
        }

        return response()->json(['status' => 'received']);
    }

    protected function processInboundMessage(string $messageId, string $from, string $text): void
    {
        $isBot = $this->detectBotBehavior($from);
        $publication = $this->extractPublicationFromText($text);
        $user = $this->findUserByPhone($from);

        $sent = false;
        $reponse = $this->defaultMessage();

        if (!$isBot) {
            if ($publication && $publication->dispositif->user?->whatsapp) {
                $proprietaire = $publication->dispositif->user;
                $ctaUrl = $this->contactOwnerUrl($proprietaire, $publication);
                $bodyText = "Merci pour votre intérêt pour « {$publication->dispositif->designation} ». "
                    . "Touchez le bouton ci-dessous pour discuter directement avec le propriétaire.";

                $sent = $this->infobip->sendWhatsappCtaButton($from, $bodyText, 'Contacter le propriétaire', $ctaUrl);

                // 🔁 Repli automatique en texte simple si le bouton échoue (endpoint indisponible, erreur, etc.)
                if (!$sent) {
                    $reponse = $this->personalizedMessage($publication) . "\n\n💬 " . $ctaUrl;
                    $sent = $this->infobip->sendWhatsappText($from, $reponse);
                }
            } else {
                $reponse = $this->defaultMessage();
                $sent = $this->infobip->sendWhatsappText($from, $reponse);
            }
        }

        $reservation = null;
        if ($sent && $publication) {
            $reservation = $this->createPendingReservation($user, $publication, $from, $text);
            $this->notifyOwner($publication, $reservation);
        }

        WhatsappInboundMessage::create([
            'message_id' => $messageId,
            'from_number' => $from,
            'user_id' => $user?->id,
            'publication_id' => $publication?->id,
            'reservation_id' => $reservation?->id,
            'message_recu' => $text,
            'reponse_envoyee' => $sent ? $reponse : null,
            'suspect_bot' => $isBot,
        ]);
    }

    protected function notifyOwner(Publication $publication, Reservation $reservation): void
    {
        $dispositif = $publication->dispositif;
        $owner = $dispositif->user;

        if (!$owner) {
            return;
        }

        $notificationMessage = "Demande de réservation (via WhatsApp) du dispositif "
            . $dispositif->designation
            . " immatriculé " . $dispositif->numero_immatriculation;

        Notification::create([
            'user_id'              => $owner->id,
            'type'                 => 'Réservation',
            'message'              => $notificationMessage,
            'send_email'           => !empty($owner->email),
            'send_email_address'   => $owner->email,
            'send_whatsapp'        => !empty($owner->whatsapp),
            'send_whatsapp_number' => $owner->whatsapp,
        ]);
    }

    /**
     * Détecte un comportement de flood/bot : plus de N messages du même numéro
     * dans une fenêtre de temps courte.
     */
    protected function detectBotBehavior(string $from): bool
    {
        $cacheKey = "whatsapp_flood_{$from}";
        $count = Cache::get($cacheKey, 0);

        Cache::put($cacheKey, $count + 1, now()->addSeconds(30));

        // Plus de 5 messages en 30 secondes → comportement suspect
        if ($count + 1 > 5) {
            Log::warning("Flood WhatsApp détecté pour {$from} ({$count} messages en 30s)");
            return true;
        }

        return false;
    }

    protected function extractPublicationFromText(string $text): ?Publication
    {
        if (!preg_match('#/publications/(\d+)#', $text, $matches)) {
            return null;
        }

        return Publication::with('dispositif.user')->find($matches[1]);
    }

    protected function findUserByPhone(string $from): ?User
    {
        $normalized = ltrim($from, '+');

        return User::where('whatsapp', 'like', "%{$normalized}")
            ->orWhere('telephone', 'like', "%{$normalized}")
            ->first();
    }

    protected function createPendingReservation(?User $user, Publication $publication, string $from, string $messageText): ?Reservation
    {
        return Reservation::create([
            'publication_id'   => $publication->id,
            'user_id'          => $user?->id, // null si pas de compte
            'date_reservation' => now()->toDateString(),
            'nom_prenom'       => $user?->nom . ' ' . $user?->prenom ?: null,
            'telephone'        => $from,
            'message'          => $messageText,
            'statut'           => 'Demandée',
        ]);
    }

    protected function personalizedMessage(Publication $publication): string
    {
        $proprietaire = $publication->dispositif->user ?? null;

        if (!$proprietaire || empty($proprietaire->whatsapp)) {
            return $this->defaultMessage();
        }

        $bodyText = "Bonjour ! Merci pour votre intérêt pour « {$publication->dispositif->designation} ».\n\n"
            . "Pour organiser la location, contactez directement le propriétaire :\n"
            . "👤 {$proprietaire->nom} {$proprietaire->prenom}\n"
            . "📞 {$proprietaire->telephone}"
            . "\n\nL'équipe RentalPark reste disponible pour toute autre question.";

        return $bodyText; // utilisé seulement si le bouton CTA échoue (voir processInboundMessage)
    }

    protected function contactOwnerUrl(User $proprietaire, Publication $publication): string
    {
        $message = "Bonjour, je vous contacte au sujet de votre annonce sur RentalPark : "
            . route('publications.show', $publication);

        return "https://wa.me/" . ltrim($proprietaire->whatsapp, '+') . "?text=" . urlencode($message);
    }

    protected function defaultMessage(): string
    {
        return "Bonjour ! Merci de nous avoir contactés. Pour toute annonce qui vous intéresse, "
            . "cliquez sur le bouton « Contacter » directement depuis la page de l'annonce sur RentalPark.";
    }
}