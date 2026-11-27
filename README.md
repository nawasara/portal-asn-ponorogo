# Portal ASN Ponorogo

Lightweight README for the Portal ASN Ponorogo web application. This repository is a Laravel application that includes Livewire for dynamic UI, Tailwind CSS for styling, and Keycloak/Socialite for SSO authentication.

**Access Portal:**  
Visit at [https://asn.ponorogo.go.id](https://asn.ponorogo.go.id)

## Contents

-   `app/` — Laravel application code (controllers, models, Livewire components)
-   `resources/views/` — Blade templates and Livewire views
-   `routes/` — web route definitions
-   `public/` — web entry (assets, index.php)
-   `tests/` — PHPUnit tests

## Quick overview

This project implements an internal portal for ASN Ponorogo with features like:

-   Keycloak SSO login/logout flows (Socialite driver)
-   App catalog (from the Nawasara panel) and help pages
-   Account management lives in Akun Kisara (asn-account.ponorogo.go.id); see below
-   Tailwind-based responsive UI with dark mode support

## Prerequisites

-   PHP 8.1+ (as required by composer.json)
-   Composer
-   Node.js + npm (for assets / Vite)
-   SQLite (included for local development) or other DB
-   Optional: Docker + Docker Compose (for containerized setup)

## Local setup (recommended)

1. Copy `.env.example` to `.env` and adjust settings (DB, APP_URL, KEYCLOAK settings):

    - `APP_URL` — your local URL (e.g. `http://localhost:8000`)
    - `DB_CONNECTION` — `sqlite` by default (see `database/database.sqlite`)
    - Keycloak/Socialite: set `KEYCLOAK_CLIENT_ID`, `KEYCLOAK_CLIENT_SECRET`, `KEYCLOAK_BASE_URL`, etc.

2. Install PHP dependencies:

    ```powershell
    composer install
    ```

3. Install JS dependencies and build assets (Vite):

    ```powershell
    npm install
    npm run dev
    ```

4. Generate app key and run migrations/seeder (if needed):

    ```powershell
    php artisan key:generate
    php artisan migrate --seed
    ```

5. Start the local server:

    ```powershell
    php artisan serve
    ```

Open your browser at the `APP_URL` value.

## Development notes

-   Livewire and Alpine are used for dynamic UI. When changing JS, run `npm run dev`.
-   Tailwind config is in `tailwind.config.js` and assets live in `resources/css` and `resources/js`.
-   Dark mode is implemented using Tailwind's `class` strategy and a small script that reads `localStorage.theme`.

## Daftar aplikasi & Akun Kisara

-   Daftar aplikasi **tidak lagi ditulis di kode**. `AppsService` mengambilnya dari katalog di panel Nawasara (`GET {NAWASARA_ASN_PORTAL_API}/public/apps`, paket `nawasara/asn-portal`), katalog yang sama dengan peluncur di Akun Kisara. Menambah atau mengubah aplikasi cukup lewat panel.
-   Disimpan di cache 5 menit (`NAWASARA_APPS_TTL`). Kalau Nawasara tidak terjangkau, portal memakai daftar terakhir yang berhasil diambil, lalu daftar bawaan di `AppsService::FALLBACK`. Beranda tidak boleh kosong hanya karena Nawasara sedang bermasalah.
-   Logo: `icon_url` dari katalog bila ada. Kalau kosong, dipakai logo lama di `public/img` (dipetakan lewat slug), lalu kotak singkatan dengan warna dari katalog.
-   Isi katalog diketik staf, jadi tautan selain `http(s)` dibuang dan warna hanya diterima dalam bentuk hex.
-   Menu **Kelola Akun Kisara** (dropdown pengguna dan halaman Profil) mengarah ke `ACCOUNT_URL`, bawaannya `https://asn-account.ponorogo.go.id`. Pengelolaan profil, keamanan, perangkat, email dinas, dan VPN dilakukan di sana.

## Reset MFA, nomor WhatsApp, passkey: pindah ke Akun Kisara

Sejak 3 November 2026 portal ini **tidak lagi mengirim kode OTP** dan tidak lagi punya halaman reset MFA, ganti nomor WhatsApp, atau daftar passkey. Semuanya ada di Akun Kisara, dan email kodenya dikirim Nawasara dari `kisara@ponorogo.go.id` (Vault grup `smtp-kisara`), alamat yang sama yang dulu dipakai portal ini.

Alamat lama (`/reset-mfa`, `/update-whatsapp-number`, `/register-passkey`) dialihkan 301 ke halaman padanannya, karena sudah tersebar di bookmark, poster, dan pesan WhatsApp. Jangan hapus pengalihan itu.

Paksaan mengisi nomor WhatsApp sebelum membuka beranda juga dihapus. Akun Kisara mengirim kode reset ke email lebih dulu, dan memaksa orang ke halaman WhatsApp selagi gateway WhatsApp mati hanya membuat mereka terkunci di luar beranda.

## Keycloak SSO

-   Login/Logout flow is centralized in `app/Http/Controllers/Auth/KeycloakController.php`.
-   Logout uses `id_token_hint` and `post_logout_redirect_uri` to redirect the user back to the portal after logging out of Keycloak.

## Sesi 30 hari (token offline)

Pegawai yang membuka portal dalam 30 hari terakhir tidak diminta masuk lagi, seperti di Kisara Mail dan Kisara Workspace. Rencana dan keputusannya ada di `docs/plan/sesi-30-hari.md` (repo nawasara-dev, tugas S-04). Logikanya di `app/Services/KeycloakSession.php`.

-   Saat masuk, portal meminta scope `offline_access` (client scope Optional di klien `asn-portal-production`). Refresh token yang didapat adalah token **offline**, yang tidak ikut berakhir saat sesi SSO realm `asn-productions` habis (idle 30 menit). Batasnya adalah Offline Session Idle realm: 30 hari.
-   Token hanya disimpan di sesi Laravel di server (driver `database`), tidak pernah di cookie yang terbaca JavaScript.
-   Sesi Laravel hidup 30 hari dan bergeser setiap kali portal dibuka (`SESSION_LIFETIME=43200` menit, juga nilai bawaan di `config/session.php`). Nilai yang lebih besar dipotong ke 43200, sebab sesi yang lebih panjang dari Offline Session Idle hanya akan memegang token yang sudah mati.
-   Access token diperbarui bila tinggal 30 detik atau sudah kedaluwarsa. **Hanya `invalid_grant` dari Keycloak yang mengeluarkan pegawai** (token dicabut, keluar dari semua perangkat, akun dinonaktifkan, idle lewat 30 hari). Galat jaringan, 5xx, atau Keycloak yang sedang restart tidak: pegawai tetap masuk dan pembaruan dicoba lagi 60 detik kemudian. Dulu portal memeriksa sesi lewat introspeksi dan ikut mengeluarkan pegawai bila introspeksi menjawab tidak aktif; pemeriksaan itu dihapus karena aturan ini.
-   Permintaan Livewire yang berjalan bersamaan memakai satu kali pembaruan per refresh token (kunci cache). Tanpa itu, bila realm memutar refresh token, permintaan kedua akan mendapat `invalid_grant` dan mengeluarkan pegawai yang sebenarnya masih sah.
-   **Keluar** mencabut refresh token di `/protocol/openid-connect/revoke`, lalu menghapus sesi Laravel, lalu mengarahkan ke end-session Keycloak. End-session saja tidak cukup: ia mengakhiri sesi SSO, sedangkan sesi offline tetap hidup. Pencabutan dibatasi 3 detik dan kegagalannya hanya dicatat di log, tidak pernah menahan pegawai tetap masuk.
-   **Masuk ulang** di peramban yang sama mencabut refresh token lama yang digantikan, **hanya bila token lama milik sesi Keycloak lain** (`sid` keduanya terbaca dan berbeda). Keycloak 26 mencabut per sesi (`sid`), jadi mencabut token lama dari sesi yang sama (masuk ulang saat sesi SSO masih hidup) ikut mematikan token yang baru diterbitkan dan pegawai keluar diam-diam pada refresh berikutnya. Bila `sid` sama atau tak terbaca, token lama dibiarkan (dicatat `login.replaced_token_kept`); Keycloak menyimpan satu sesi offline per sesi SSO dan klien, jadi tidak menumpuk. Pencabutan ini upaya terbaik dan tidak pernah menghalangi login. Aturannya sama dengan `replacedTokenStep` di kisara-workspace-web.
-   **Remember-me Laravel dihapus.** Token offline dan sesi 30 hari sudah menjadi kredensial jangka panjangnya; cookie remember-me akan menjadi kredensial kedua yang tidak ikut berakhir saat Keluar. Sesi yang dipulihkan dari cookie remember-me lama tidak membawa refresh token, jadi pemeriksaan sesi langsung mengeluarkannya dan Laravel menghapus cookie itu.
-   Modal "Sesi Anda berakhir" (`SessionModal`) kini muncul hanya ketika Keycloak menolak refresh token. Keluar dari aplikasi lain di realm yang sama atau SSO idle 30 menit tidak lagi mengeluarkan pegawai dari portal; itu konsekuensi yang diterima pemilik. Di komputer bersama pegawai wajib menekan Keluar.

Log yang perlu dipantau: `session.ended` (invalid_grant), `session.refresh_failed` (Keycloak tak terjangkau, coba lagi), `logout.revoke_failed` dan `login.revoke_failed` (pencabutan gagal, token tetap hidup sampai idle 30 hari), serta `login.succeeded` dengan `offline=false` (scope `offline_access` tidak diberikan, berarti klien atau peran `offline_access` di Keycloak berubah).

### Saat deploy

-   Ubah `.env` produksi: `SESSION_LIFETIME=43200`. Nilai lama `120` membuat sesi tetap berakhir setelah 2 jam tanpa aktivitas. Kalau barisnya dihapus, bawaan config juga 43200.
-   Pegawai yang sedang masuk saat deploy masih memegang refresh token biasa. Mereka diminta masuk sekali lagi ketika sesi SSO lamanya habis; setelah itu sesinya 30 hari.
-   Tabel `sessions` menampung baris sampai 30 hari; pembersihannya tetap lewat lotre sesi bawaan Laravel.

## Tests

-   Run PHPUnit tests with:

    ```powershell
    ./vendor/bin/phpunit
    ```

## Common tasks & commands

-   Run migrations: `php artisan migrate`
-   Run seeders: `php artisan db:seed`
-   Build assets for production: `npm run build`

## Troubleshooting

-   Dark mode not toggling: check `resources/views/components/layouts/app.blade.php` for the global dark-mode script and ensure no page-level scripts overwrite it.
-   Missing Alpine features: Livewire may bundle Alpine; avoid loading conflicting Alpine versions.

## Contribution

-   Keep UI components reusable (Blade/Livewire components), centralize auth logic into controllers, and write tests for Livewire behavior where possible.

## License

This project inherits licensing from upstream dependencies. Add a license file if this repository needs a specific license.

---

If you want I can:

-   Add a quick start script that automates installation for Windows (PowerShell), or
-   Add a CONTRIBUTING.md and PR template.
