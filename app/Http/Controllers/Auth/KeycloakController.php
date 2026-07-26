<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Constants\Constants;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class KeycloakController extends \App\Http\Controllers\Controller
{
    use SessionTrait;

    public function redirectToProvider()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    /**
     * Arahkan user langsung ke halaman SETUP PASSKEY Keycloak (ber-theme asn-v2),
     * TANPA lewat account console native. Pakai parameter OIDC `kc_action=
     * webauthn-register-passwordless`: Keycloak jalankan required-action register
     * passkey lalu redirect balik ke portal (callback biasa).
     *
     * User biasanya sudah punya sesi SSO aktif → Keycloak cukup memicu setup
     * (kadang minta re-auth singkat), tidak login ulang penuh.
     */
    public function redirectToRegisterPasskey()
    {
        return Socialite::driver('keycloak')
            ->with(['kc_action' => 'webauthn-register-passwordless'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('keycloak')->user();

            $authUser = User::firstOrCreate([
                'email' => $user->getEmail(),
            ], [
                'name' => $user->getName(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($authUser, true);

            $tokenResponse = $user->accessTokenResponseBody;

            Session::put('keycloak_id_token', $tokenResponse['id_token']);
            Session::put('keycloak_access_token', $tokenResponse['access_token']);
            Session::put('keycloak_refresh_token', $tokenResponse['refresh_token'] ?? null);
            Session::put('keycloak_token_expires_at', now()->addSeconds($tokenResponse['expires_in'] ?? 300)->timestamp);
            Session::put('keycloak_id_user', $user->id);

            return redirect('/');
        } catch (\Exception $e) {
            return redirect()->route('index');
        }
    }

    public function logout(Request $request)
    {
        // Keycloak logout
        $service = new KeycloakService();
        $url = $service->logout();

        $this->logoutLaravel();
        return $url ? redirect($url) : redirect('/');
    }
}
