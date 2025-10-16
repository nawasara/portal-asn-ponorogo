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
            self::logoutLaravel();
        }

        try {
            $service = new KeycloakService();
            $res = $service->checkLoginStatus($token);
            // if (!$res['active']) {
            //     self::logout();
            // }

            return $res;
        } catch (\Exception $e) {
            self::logoutLaravel();
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
        if (!$number) {
            $this->redirectRoute('update-whatsapp-number');
            return;
        }

        return $number;
    }
    
    public function logoutLaravel()
    {
        Auth::logout();
        Session::flush(); // Clear the session data
        Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks  
        return;
    }  
}