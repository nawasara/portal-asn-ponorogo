<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class KeycloakService
{
    protected $baseUrl;
    protected $realm;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl      = env('KEYCLOAK_BASE_URL');
        $this->realm        = env('KEYCLOAK_REALM');
        $this->clientId     = env('KEYCLOAK_CLIENT_ID');
        $this->clientSecret = env('KEYCLOAK_CLIENT_SECRET');
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
     * Update WhatsApp Number user
     */
    public function updateWhatsappNumber($userId, $whatsappNumber)
    {
        $token = $this->getAdminToken();

        // Ambil data user saat ini
        $userResponse = Http::withToken($token)
            ->get("{$this->baseUrl}/admin/realms/{$this->realm}/users/{$userId}");

        if ($userResponse->failed()) {
            throw new \Exception('Gagal mengambil data user dari Keycloak');
        }

        $userData = $userResponse->json();

        // Pastikan attributes tidak null
        $attributes = $userData['attributes'] ?? [];

        // Update / tambah whatsapp_number
        $attributes['whatsapp_number'] = [$whatsappNumber];
        $attributes['whatsapp_verified_at'] = [now()->toDateTimeString()];

        // Merge kembali ke data user
        $userData['attributes'] = $attributes;

        // Kirim update ke Keycloak
        $response = Http::withToken($token)
            ->put("{$this->baseUrl}/admin/realms/{$this->realm}/users/{$userId}", $userData);

        if ($response->failed()) {
            throw new \Exception('Gagal update nomor WhatsApp di Keycloak');
        }

        return true;
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


}
