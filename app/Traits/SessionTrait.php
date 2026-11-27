<?php

namespace App\Traits;

use App\Services\KeycloakService;
use App\Services\KeycloakSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait SessionTrait
{
    /**
     * Is the employee's Keycloak session still alive? Refreshes the access
     * token when it is about to expire (KeycloakSession::ensureFresh).
     *
     * Signs the employee out of the portal only when the session has no
     * refresh token (a session from before tokens were stored, or one
     * restored from an old remember-me cookie) or Keycloak answered
     * invalid_grant. A network error or a Keycloak restart keeps them in.
     *
     * Keycloak is not called on every page view: only when the access token
     * is about to expire, at most once a minute after a failure.
     */
    public function checkKeycloakSession(): array
    {
        if (app(KeycloakSession::class)->ensureFresh() === KeycloakSession::ENDED) {
            self::logoutLaravel();

            return ['active' => false];
        }

        return ['active' => true];
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

    /**
     * End the portal session. invalidate() also DESTROYS the old row in the
     * session store; flush() + regenerate() only moved to a new id and left
     * the old row (user + Keycloak tokens) behind, so a cookie copied from a
     * shared computer kept working after Keluar for up to 30 days.
     */
    public function logoutLaravel()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    }
}
