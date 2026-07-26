<?php

namespace App\Livewire\SharedComponents;

use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

/**
 * Banner ajakan daftar Passkey.
 *
 * Muncul HANYA untuk user yang sudah login TAPI belum punya passkey
 * (credential webauthn-passwordless di Keycloak). Pengecekan di server —
 * bukan cuma localStorage — supaya begitu user sudah daftar, banner hilang
 * otomatis di semua device.
 *
 * Alur rollout (ide "daftar setelah login, dipandu via portal"):
 *   login password normal → masuk portal → banner → klik "Daftar Passkey"
 *   → Account Console Keycloak (setup) → kembali → login berikutnya bisa passkey.
 */
class PasskeyBanner extends Component
{
    public bool $show = false;
    public string $setupUrl = '';

    public function mount(): void
    {
        if (!Auth::check()) {
            return;
        }

        $userId = Session::get('keycloak_id_user');
        if (!$userId) {
            return;
        }

        $service = new KeycloakService();
        // Tampilkan banner hanya kalau user BELUM punya passkey.
        $this->show = ! $service->hasPasskey($userId);
        $this->setupUrl = $service->passkeySetupUrl();
    }

    public function render()
    {
        return view('livewire.shared-components.passkey-banner');
    }
}
