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
        // Round 1, online. No offline_access here: as the first app of an SSO
        // session it would end that session for every other app.
        // See KeycloakSession::SCOPES.
        return Socialite::driver('keycloak')->scopes(KeycloakSession::SCOPES)->redirect();
    }

    /**
     * Round 2, silent: ask for the offline token with prompt=none. Keycloak
     * answers with a code at once (the SSO session is fresh), or with an
     * error that the callback treats as "stay on the online token".
     */
    public function redirectForOfflineToken()
    {
        $pendingSince = Session::pull(KeycloakSession::KEY_OFFLINE_PENDING);
        if (! Auth::check() || ! self::isFreshFlag($pendingSince)) {
            return redirect('/');
        }

        Session::put(KeycloakSession::KEY_OFFLINE_ROUND, now()->timestamp);

        return Socialite::driver('keycloak')
            ->scopes(KeycloakSession::OFFLINE_SCOPES)
            ->with(['prompt' => 'none'])
            ->redirect();
    }

    public function handleProviderCallback(Request $request, KeycloakSession $tokens)
    {
        if (self::isFreshFlag(Session::pull(KeycloakSession::KEY_OFFLINE_ROUND)) && Auth::check()) {
            return $this->finishOfflineRound($request, $tokens);
        }

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

        // Signed in on an online token. Now the silent round for the 30-day
        // offline token (redirectForOfflineToken).
        Session::put(KeycloakSession::KEY_OFFLINE_PENDING, now()->timestamp);

        return redirect()->route('login.offline');
    }

    /**
     * Callback of the silent round. The employee is already signed in on an
     * online token; every failure here keeps that, never signs them out.
     * The online refresh token is NOT revoked: it shares its `sid` with the
     * new offline token, and Keycloak 26 revokes per session.
     */
    protected function finishOfflineRound(Request $request, KeycloakSession $tokens)
    {
        $userId = Auth::id();

        if ($request->filled('error')) {
            // login_required: the SSO session ended between the rounds.
            Log::info('login.offline_skipped', ['user_id' => $userId, 'reason' => (string) $request->query('error')]);

            return redirect('/');
        }

        try {
            $user = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            Log::warning('login.offline_skipped', ['user_id' => $userId, 'reason' => class_basename($e)]);

            return redirect('/');
        }

        if ($user->getId() !== Session::get(KeycloakSession::KEY_USER_ID)) {
            // Another account answered (someone signed in elsewhere in this
            // browser in between). Keep the session that was signed in.
            Log::warning('login.offline_skipped', ['user_id' => $userId, 'reason' => 'subject_mismatch']);

            return redirect('/');
        }

        $tokenResponse = $user->accessTokenResponseBody;
        $tokens->store($tokenResponse);
        Log::info('login.offline_upgraded', [
            'user_id' => $userId,
            'offline' => KeycloakSession::isOfflineToken($tokenResponse['refresh_token'] ?? null),
        ]);

        return redirect('/');
    }

    private static function isFreshFlag(mixed $since): bool
    {
        return is_int($since) && now()->timestamp - $since <= KeycloakSession::OFFLINE_ROUND_TTL_S;
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
