<?php

namespace App\Traits;

use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

trait SessionTrait
{
    public function checkKeycloakSession()
    {
        $accessToken = Session::get('keycloak_access_token');

        // Backward compat: kalau session lama belum punya access_token,
        // anggap session expired supaya user re-login.
        if (!$accessToken) {
            self::logoutLaravel();
            return ['active' => false];
        }

        // Cache hasil 30 detik per session ID supaya navigasi tidak hit Keycloak berulang.
        $cacheKey = 'kc:session:' . Session::getId();
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Refresh access_token kalau sudah expired sebelum introspect.
        $expiresAt = Session::get('keycloak_token_expires_at');
        if ($expiresAt && now()->timestamp >= $expiresAt) {
            $refreshed = self::refreshKeycloakToken();
            if (!$refreshed) {
                self::logoutLaravel();
                $result = ['active' => false];
                Cache::put($cacheKey, $result, 30);
                return $result;
            }
            $accessToken = Session::get('keycloak_access_token');
        }

        try {
            $service = new KeycloakService();
            $res = $service->checkLoginStatus($accessToken);
            Cache::put($cacheKey, $res, 30);
            return $res;
        } catch (\Exception $e) {
            // Jangan langsung logout kalau Keycloak unreachable — bisa false positive
            // saat jaringan flaky. Logout hanya saat introspect tegas return active=false.
            Log::warning('Keycloak introspect gagal: ' . $e->getMessage());
            return ['active' => true, 'error' => $e->getMessage()];
        }
    }

    /**
     * Refresh access_token pakai refresh_token yang tersimpan.
     * Return true kalau sukses, false kalau refresh_token juga invalid.
     */
    protected function refreshKeycloakToken(): bool
    {
        $refreshToken = Session::get('keycloak_refresh_token');
        if (!$refreshToken) {
            return false;
        }

        $baseUrl = config('services.keycloak.base_url');
        $realm = config('services.keycloak.realms');

        try {
            $response = Http::asForm()->post(
                "{$baseUrl}/realms/{$realm}/protocol/openid-connect/token",
                [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('services.keycloak.client_id'),
                    'client_secret' => config('services.keycloak.client_secret'),
                    'refresh_token' => $refreshToken,
                ]
            );

            if ($response->failed()) {
                Log::info('Keycloak refresh token gagal: ' . $response->body());
                return false;
            }

            $data = $response->json();
            Session::put('keycloak_access_token', $data['access_token']);
            Session::put('keycloak_refresh_token', $data['refresh_token'] ?? $refreshToken);
            Session::put('keycloak_token_expires_at', now()->addSeconds($data['expires_in'] ?? 300)->timestamp);

            Cache::forget('kc:session:' . Session::getId());

            return true;
        } catch (\Exception $e) {
            Log::warning('Keycloak refresh token exception: ' . $e->getMessage());
            return false;
        }
    }

    public function getKeycloakUser()
    {
        try {
            $token = Session::get('keycloak_id_user');
            $service = new KeycloakService();
            return $service->getUser($token);
        } catch (\Exception $e) {
            info('Gagal mengambil info user dari Keycloak: ' . $e->getMessage());
            self::logoutLaravel();
        }
    }

    public function getNumber()
    {
        $token = Session::get('keycloak_id_user');

        $service = new KeycloakService();
        $number = $service->getWhatsappNumber($token);
        if (!$number) info("get number null");
        return $number ?? null;
    }

    public function logoutLaravel()
    {
        Auth::logout();
        Session::flush();
        Session::regenerate();
        return;
    }
}
