<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class KeycloakService
{
    protected $baseUrl;
    protected $realm;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl      = config('services.keycloak.base_url');
        $this->realm        = config('services.keycloak.realms');
        $this->clientId     = config('services.keycloak.client_id');
        $this->clientSecret = config('services.keycloak.client_secret');
    }

    /**
     * Ambil access token admin Keycloak
     */
    protected function getAdminToken()
    {
        return Cache::remember('keycloak_admin_token', 55, function () {
            $response = Http::asForm()->post("{$this->baseUrl}/realms/{$this->realm}/protocol/openid-connect/token", [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->failed()) {
                throw new \Exception('Gagal mendapatkan admin token dari Keycloak');
            }

            return $response->json()['access_token'];
        });
    }

    /**
     * Get user detail by userId
     */
    public function getUser($userId)
    {
        $token = $this->getAdminToken();

        $response = Http::withToken($token)->get("{$this->baseUrl}/admin/realms/{$this->realm}/users/{$userId}");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data user dari Keycloak');
        }

        return $response->json();
    }

    /**
     * Get WhatsApp Number dari user profile
     */
    public function getWhatsappNumber($userId)
    {
        $user = $this->getUser($userId);

        return $user['attributes']['whatsapp_number'][0] ?? null;
    }

    /**
     * Get email dari user profile Keycloak (field top-level standar).
     */
    public function getEmail($userId): ?string
    {
        $user = $this->getUser($userId);

        return $user['email'] ?? null;
    }

    /**
     * Cek status login user berdasarkan access token 
     */
    public function checkLoginStatus(string $accessToken): array
    {
        $response = Http::asForm()->post("{$this->baseUrl}/realms/{$this->realm}/protocol/openid-connect/token/introspect", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'token' => $accessToken,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal cek status login di Keycloak');
        }

        return $response->json();
    }

    /**
     * Cek apakah user sudah punya passkey (credential webauthn-passwordless)
     * di Keycloak. Dipakai untuk menampilkan banner "Daftar Passkey" HANYA
     * untuk user yang belum mendaftar.
     */
    public function hasPasskey(string $userId): bool
    {
        try {
            $token = $this->getAdminToken();
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/admin/realms/{$this->realm}/users/{$userId}/credentials");

            if ($response->failed()) {
                return false; // gagal cek → jangan ganggu user dengan banner
            }

            foreach ($response->json() as $credential) {
                if (($credential['type'] ?? null) === 'webauthn-passwordless') {
                    return true;
                }
            }
            return false;
        } catch (\Throwable $e) {
            info('Gagal cek passkey user: ' . $e->getMessage());
            return false;
        }
    }

    /* logout keycloak url */
    public function logout()
    {
        $keycloakIdToken = Session::get('keycloak_id_token');
        if (!$keycloakIdToken) return;

        // The URL the user is redirected to after logout.
        $redirectUri = Config::get('app.url');
        $url = Socialite::driver('keycloak')->getLogoutUrl();
        $params = [
            'id_token_hint' => $keycloakIdToken, // Ambil id_token dari session
            'post_logout_redirect_uri' => $redirectUri, // URL redirect setelah logout
        ];

        $url .= '?' . http_build_query($params);

        return $url;
    }  
}
