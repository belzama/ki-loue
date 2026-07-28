<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class InfobipService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.infobip.base_url'), '/');
        $this->apiKey = config('services.infobip.api_key');
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'App ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    // ── SMS ──────────────────────────────────────────────
    public function sendSms(string $to, string $text): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/sms/2/text/advanced", [
                    'messages' => [[
                        'from' => config('services.infobip.sms_sender'),
                        'destinations' => [['to' => $this->normalizeNumber($to)]],
                        'text' => $text,
                    ]],
                ]);

            if ($response->failed()) {
                Log::error('Infobip SMS échec', ['response' => $response->body()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Infobip SMS exception : ' . $e->getMessage());
            return false;
        }
    }

    public function sendWhatsappText(string $to, string $text): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/whatsapp/1/message/text", [
                    'from' => config('services.infobip.whatsapp_sender'),
                    'to' => $this->normalizeNumber($to),
                    'content' => ['text' => $text],
                ]);

            if ($response->failed()) {
                Log::error('Infobip WhatsApp texte échec', ['response' => $response->body()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Infobip WhatsApp texte exception : ' . $e->getMessage());
            return false;
        }
    }

    public function sendWhatsappCtaButton(string $to, string $bodyText, string $buttonLabel, string $url): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/whatsapp/1/message/interactive/buttons/url", [
                    'from' => config('services.infobip.whatsapp_sender'),
                    'to' => $this->normalizeNumber($to),
                    'content' => [
                        'body' => ['text' => $bodyText],
                        'action' => [
                            'name' => 'cta_url',
                            'parameters' => [
                                'displayText' => $buttonLabel,
                                'url' => $url,
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('Infobip CTA button échec, fallback texte', ['response' => $response->body()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Infobip CTA button exception : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoi WhatsApp générique via un template enregistré dans config('services.infobip.whatsapp_templates').
     *
     * @param string $to                Numéro destinataire
     * @param string $templateKey       Clé du template dans la config (ex: 'verification_code')
     * @param array  $bodyPlaceholders  Valeurs à injecter dans le corps du template, dans l'ordre
     * @param array  $options           Options avancées optionnelles : ['header' => [...], 'buttons' => [...]]
     */
    public function sendWhatsappTemplate(
        string $to,
        string $templateKey,
        array $bodyPlaceholders = [],
        array $options = []
    ): bool {
        $templates = config('services.infobip.whatsapp_templates', []);

        if (!isset($templates[$templateKey])) {
            throw new InvalidArgumentException("Template WhatsApp inconnu : {$templateKey}. Ajoute-le dans config/services.php.");
        }

        $template = $templates[$templateKey];

        $content = [
            'templateName' => $template['name'],
            'templateData' => [
                'body' => ['placeholders' => $bodyPlaceholders],
            ],
            'language' => $options['language'] ?? $template['language'] ?? 'fr',
        ];

        if (isset($options['header'])) {
            $content['templateData']['header'] = $options['header'];
        }

        if (isset($options['buttons'])) {
            $content['templateData']['buttons'] = $options['buttons'];
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/whatsapp/1/message/template", [
                    'messages' => [[
                        'from' => config('services.infobip.whatsapp_sender'),
                        'to' => $this->normalizeNumber($to),
                        'content' => $content,
                    ]],
                ]);

            if ($response->failed()) {
                Log::error('Infobip WhatsApp échec', [
                    'template' => $templateKey,
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error("Infobip WhatsApp exception ({$templateKey}) : " . $e->getMessage());
            return false;
        }
    }

    protected function normalizeNumber(string $number): string
    {
        return preg_replace('/[^0-9]/', '', $number);
    }
}