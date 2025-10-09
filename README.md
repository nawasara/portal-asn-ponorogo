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
-   User profile, dashboard and MFA/OTP flows using WhatsApp
-   Livewire components for OTP handling and interactive forms
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

## OTP / WhatsApp flow

-   The application sends OTP codes to WhatsApp numbers via an internal `WaNotificationService` (or similar). OTP state is cached with TTL and rate-limited.
-   The OTP UI is implemented as a reusable Livewire component at `app/Livewire/Components/OtpForm.php` and view `resources/views/livewire/components/otp-form.blade.php`.

## Keycloak SSO

-   Login/Logout flow is centralized in `app/Http/Controllers/Auth/KeycloakController.php`.
-   Logout uses `id_token_hint` and `post_logout_redirect_uri` to redirect the user back to the portal after logging out of Keycloak.

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
-   OTP not sent: verify `WaNotificationService` credentials and that `cache` is writable.

## Contribution

-   Keep UI components reusable (Blade/Livewire components), centralize auth logic into controllers, and write tests for Livewire behavior where possible.

## License

This project inherits licensing from upstream dependencies. Add a license file if this repository needs a specific license.

---

If you want I can:

-   Add a quick start script that automates installation for Windows (PowerShell), or
-   Add a CONTRIBUTING.md and PR template.
