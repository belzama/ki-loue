<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'infobip' => [
        'base_url' => env('INFOBIP_BASE_URL'),
        'api_key' => env('INFOBIP_API_KEY'),
        'sms_sender' => env('INFOBIP_SMS_SENDER'),
        'whatsapp_sender' => env('INFOBIP_WHATSAPP_SENDER'),

        // 📋 Registre central de tous les templates WhatsApp du projet
        'whatsapp_templates' => [
            'verification_code' => [
                'name' => env('INFOBIP_TEMPLATE_VERIFICATION', 'verification_code'),
                'language' => 'fr',
                'cout' => 0,
            ],
            'reservation_confirmed' => [
                'name' => env('INFOBIP_TEMPLATE_RESERVATION', 'reservation_confirmed'),
                'language' => 'fr',
                'cout' => 25,
            ],
            'payment_reminder' => [
                'name' => env('INFOBIP_TEMPLATE_PAYMENT', 'payment_reminder'),
                'language' => 'fr',
                'cout' => 25,
            ],
            // ➕ ajoute une entrée ici à chaque nouveau template créé sur Infobip
        ],
    ],

];
