<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Services\KeycloakService;
use App\Services\KeycloakSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class KeycloakController extends \App\Http\Controllers\Controller
{
    use SessionTrait;

    public function redirectToProvider()
    {
        // No offline_access: it ends the SSO session for every other app.
        // See KeycloakSession::SCOPES.
        return Socialite::driver('keycloak')->scopes(KeycloakSession::SCOPES)->redirect();
    }

    public function handleProviderCallback(KeycloakSession $tokens)
    {
        try {
            $user = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            Log::warning('login.callback_failed', ['reason' => class_basename($e)]);

            return redirect()->route('index');
        }

        $authUser = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => $user->getName(),
            'password' => bcrypt(Str::random(16)),
        ]);

        // No remember-me: the Keycloak offline token and the 30-day session
        // are the long-lived credential. A remember cookie would be a second
        // one that Keluar at Keycloak cannot end.
        Auth::login($authUser);

        $tokenResponse = $user->accessTokenResponseBody;
        $previousRefreshToken = Session::get(KeycloakSession::KEY_REFRESH_TOKEN);
        $tokens->store($tokenResponse, $user->getId());

        // A new sign-in replaces the offline session of the previous one in
        // this browser; revoke that token so it does not live on unused, but
        // only when it belongs to ANOTHER Keycloak session: Keycloak 26
        // revokes per session, so revoking a token of the same session would
        // also end the one just issued. Best effort: never blocks the sign-in.
        $step = KeycloakSession::replacedTokenStep($previousRefreshToken, $tokenResponse['refresh_token'] ?? null);
        if ($step === 'revoke') {
            $tokens->revoke($previousRefreshToken, 'login');
        } elseif ($step !== 'none') {
            Log::info('login.replaced_token_kept', ['user_id' => $authUser->id, 'reason' => $step]);
        }

        Log::info('login.succeeded', [
            'user_id' => $authUser->id,
            'offline' => KeycloakSession::isOfflineToken($tokenResponse['refresh_token'] ?? null),
        ]);

        return redirect('/');
    }

    /**
     * Keluar: revoke the refresh token (ends the offline session at
     * Keycloak), end the Laravel session, then send the browser to the
     * Keycloak end-session endpoint to end the SSO session too.
     */
    public function logout(Request $request, KeycloakSession $tokens)
    {
        $tokens->revoke(Session::get(KeycloakSession::KEY_REFRESH_TOKEN), 'logout');

        // Built before the session is cleared: it needs the id_token.
        $url = (new KeycloakService())->logout();

        $this->logoutLaravel();

        return $url ? redirect($url) : redirect('/');
    }
}
