<?php

/**
 * Tautan ke Akun Kisara (asn-account.ponorogo.go.id). Profil, keamanan, reset
 * verifikasi dua langkah, nomor WhatsApp, dan passkey dikelola di sana.
 */
function account_url(string $path = ''): string {
    return rtrim((string) config('services.account.url'), '/') . $path;
}
