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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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
    'keycloak' => [
        'client_id' => env('KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
        'redirect' => env('KEYCLOAK_REDIRECT_URI'),
        'base_url' => env('KEYCLOAK_BASE_URL'),   // Specify your keycloak server URL here
        'realms' => env('KEYCLOAK_REALM')         // Specify your keycloak realm
    ],

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL'),
        'api_key' => env('WHATSAPP_API_KEY'),

        // Saat WAGO tidak tersedia (mis. nomor kena ban), set false untuk
        // melewati cek nomor + pengiriman OTP. Form input WA TETAP muncul
        // dan nomor TETAP disimpan — hanya verifikasinya yang dilewati.
        'verification_enabled' => env('WHATSAPP_VERIFICATION_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gmail SMTP pool (untuk pengiriman OTP email)
    |--------------------------------------------------------------------------
    | Daftar akun Gmail untuk kirim email OTP secara random + failover, agar
    | tidak bergantung pada satu akun (kena limit harian / diblok).
    |
    | Isi MAIL_GMAIL_POOL di .env dengan JSON array. Tiap item:
    |   { "user": "akun@gmail.com", "pass": "app-password-16char", "from": "akun@gmail.com", "name": "SSO ASN Ponorogo" }
    | "pass" = Google App Password (butuh 2FA aktif), BUKAN password login biasa.
    | "from" & "name" opsional (default ke user / nama global).
    |
    | Contoh (satu baris di .env):
    |   MAIL_GMAIL_POOL='[{"user":"a@gmail.com","pass":"xxxx xxxx xxxx xxxx"},{"user":"b@gmail.com","pass":"yyyy yyyy yyyy yyyy"}]'
    */
    'gmail_pool' => [
        'accounts' => array_values(array_filter(
            json_decode((string) env('MAIL_GMAIL_POOL', '[]'), true) ?: [],
            fn ($a) => is_array($a) && !empty($a['user']) && !empty($a['pass'])
        )),
        'host' => env('MAIL_GMAIL_HOST', 'smtp.gmail.com'),
        'port' => (int) env('MAIL_GMAIL_PORT', 587),
    ],

];
