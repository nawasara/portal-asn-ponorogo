<?php

namespace App\Traits;

use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

trait SessionTrait
{
    public function checkKeycloakSession()
    {
        $token = Session::get('keycloak_id_token');
        if (!$token) {
            self::logout();
        }

        try {
            $service = new KeycloakService();
            $res = $service->checkLoginStatus($token);
            // if (!$res['active']) {
            //     self::logout();
            // }

            return $res;
        } catch (\Exception $e) {
            self::logout();
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
            self::logout();
        }
    }

    public function logout($redirect = true)
    {
        Auth::logout();
        Session::flush(); // Clear the session data
        Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks  

        $keycloakIdToken = Session::get('keycloak_id_token');
        if ($keycloakIdToken) {
            // The URL the user is redirected to after logout.
            $redirectUri = Config::get('app.url');
            $url = Socialite::driver('keycloak')->getLogoutUrl();
            $params = [
                'id_token_hint' => $keycloakIdToken, // Ambil id_token dari session
                'post_logout_redirect_uri' => $redirectUri, // URL redirect setelah logout
            ];

            $url .= '?' . http_build_query($params);

            // return redirect($url);
        }
    }

    public function getNumber()
    {
        $token = Session::get('keycloak_id_user');

        $service = new KeycloakService();
        $number = $service->getWhatsappNumber($token);
        if (!$number) {
            return redirect()->route('update-whatsapp-number');
        }

        return $number;
    }
}