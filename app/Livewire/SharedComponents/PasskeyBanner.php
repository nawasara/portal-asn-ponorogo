<?php

namespace App\Livewire\SharedComponents;

use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
 *   → halaman Passkey di Akun Kisara → login berikutnya bisa passkey.
 */
class PasskeyBanner extends Component
{
    public const CACHE_PREFIX = 'passkey:';
    public const PASSKEY_MISSING_TTL = 3600;
    public const PASSKEY_PRESENT_TTL = 86400;

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

        // Tampilkan banner hanya kalau user BELUM punya passkey. Kalau
        // Keycloak tidak bisa ditanya, banner tidak tampil dan halaman tetap jalan.
        $this->show = $this->passkeyStatus($userId) === false;
        $this->setupUrl = account_url('/security/passkeys');
    }

    /**
     * Status passkey dari cache, supaya beranda tidak memanggil admin API
     * Keycloak setiap kali dibuka. "Belum punya" disimpan PASSKEY_MISSING_TTL
     * saja, sehingga banner hilang paling lambat sejam setelah pegawai
     * mendaftar passkey. "Sudah punya" disimpan sehari. Kegagalan (null)
     * tidak disimpan: dicoba lagi pada kunjungan berikutnya.
     */
    protected function passkeyStatus(string $userId): ?bool
    {
        $key = self::CACHE_PREFIX.hash('sha256', $userId);
        $cached = Cache::get($key);
        if ($cached === 'yes' || $cached === 'no') {
            return $cached === 'yes';
        }

        $status = (new KeycloakService())->passkeyStatus($userId);
        if ($status !== null) {
            Cache::put($key, $status ? 'yes' : 'no', $status ? self::PASSKEY_PRESENT_TTL : self::PASSKEY_MISSING_TTL);
        }

        return $status;
    }

    public function render()
    {
        return view('livewire.shared-components.passkey-banner');
    }
}
