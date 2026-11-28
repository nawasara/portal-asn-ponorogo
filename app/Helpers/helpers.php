<?php

/**
 * Tautan ke Akun Kisara (asn-account.ponorogo.go.id). Profil, keamanan, reset
 * verifikasi dua langkah, nomor WhatsApp, dan passkey dikelola di sana.
 *
 * Tanpa path: Beranda (`/home`), BUKAN akar situs. Akar Akun Kisara adalah
 * landing untuk tamu, jadi pegawai yang belum punya sesi di sana harus
 * menekan Masuk lagi. `/home` dan halaman lain otomatis masuk lewat SSO.
 */
function account_url(string $path = ''): string {
    return rtrim((string) config('services.account.url'), '/') . ($path === '' ? '/home' : $path);
}
