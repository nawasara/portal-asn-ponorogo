<?php

namespace App\Services;

use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Keycloak tokens of the signed-in employee, kept in the Laravel session
 * (server side, never in a cookie the browser can read).
 *
 * The session is backed by a Keycloak OFFLINE token (scope offline_access),
 * like Kisara Mail and Kisara Workspace. The realm asn-productions ends an
 * ordinary SSO session after 30 minutes idle; its offline session idle is
 * 30 days. So the portal session slides 30 days (SESSION_LIFETIME) from the
 * last visit, and the employee signs in again only after a month away, or
 * when Keycloak says the session is gone.
 *
 * Rules (docs/plan/sesi-30-hari.md in nawasara-dev, FR-01..FR-04):
 * - the access token is refreshed when it is about to expire;
 * - only invalid_grant from the token endpoint ends the session; a network
 *   error, a 5xx or a Keycloak restart keeps the employee signed in and the
 *   refresh is tried again REFRESH_RETRY_S later;
 * - Keluar revokes the refresh token, best effort; a new sign-in revokes
 *   the token it replaces when that belongs to another Keycloak session.
 */
class KeycloakSession
{
    /** Refresh this many seconds before the access token actually expires. */
    public const REFRESH_MARGIN_S = 30;

    /** After a refresh failed for a reason other than invalid_grant, wait this long before trying again. */
    public const REFRESH_RETRY_S = 60;

    /** Keluar and sign-in wait at most this long for Keycloak to revoke a token. */
    public const REVOKE_TIMEOUT_S = 3;

    /** The token endpoint gets this long before the refresh counts as a network failure. */
    public const REFRESH_TIMEOUT_S = 10;

    /** Scope added to the provider's default `openid`. */
    public const SCOPES = ['offline_access'];

    /** Results of ensureFresh(). */
    public const ACTIVE = 'active';
    public const ENDED = 'ended';

    public const KEY_ID_TOKEN = 'keycloak_id_token';
    public const KEY_ACCESS_TOKEN = 'keycloak_access_token';
    public const KEY_REFRESH_TOKEN = 'keycloak_refresh_token';
    public const KEY_EXPIRES_AT = 'keycloak_token_expires_at';
    public const KEY_RETRY_AT = 'keycloak_refresh_retry_at';
    public const KEY_USER_ID = 'keycloak_id_user';

    /**
     * What to do with the access token now.
     * - `use`: still good.
     * - `refresh`: (about to be) expired; ask Keycloak for a new one.
     * - `wait`: expired, but the last refresh failed on something other than
     *   invalid_grant less than REFRESH_RETRY_S ago. Keep the session.
     */
    public static function refreshStep(?int $accessExpiresAt, ?int $retryAt, int $now): string
    {
        if ($accessExpiresAt !== null && $accessExpiresAt - self::REFRESH_MARGIN_S > $now) {
            return 'use';
        }
        if ($retryAt !== null && $retryAt > $now) {
            return 'wait';
        }

        return 'refresh';
    }

    /**
     * Save the tokens from the authorization code exchange or a refresh.
     * Keycloak may omit a new refresh or id token on refresh; the old one is kept.
     */
    public function store(array $tokens, ?string $keycloakUserId = null): void
    {
        Session::put(self::KEY_ACCESS_TOKEN, $tokens['access_token']);
        Session::put(self::KEY_EXPIRES_AT, now()->addSeconds((int) ($tokens['expires_in'] ?? 300))->timestamp);
        if (! empty($tokens['refresh_token'])) {
            Session::put(self::KEY_REFRESH_TOKEN, $tokens['refresh_token']);
        }
        if (! empty($tokens['id_token'])) {
            Session::put(self::KEY_ID_TOKEN, $tokens['id_token']);
        }
        if ($keycloakUserId !== null) {
            Session::put(self::KEY_USER_ID, $keycloakUserId);
        }
        Session::forget(self::KEY_RETRY_AT);
    }

    /**
     * Keep the session's access token usable. Returns ENDED only when the
     * session has no refresh token or Keycloak answered invalid_grant;
     * every other failure returns ACTIVE and schedules a retry.
     */
    public function ensureFresh(): string
    {
        $refreshToken = Session::get(self::KEY_REFRESH_TOKEN);
        if (! $refreshToken) {
            return self::ENDED;
        }

        $step = self::refreshStep(
            Session::get(self::KEY_EXPIRES_AT),
            Session::get(self::KEY_RETRY_AT),
            now()->timestamp,
        );
        if ($step !== 'refresh') {
            return self::ACTIVE;
        }

        $result = $this->refreshOnce($refreshToken);

        if ($result['outcome'] === 'ok') {
            $this->store($result['tokens']);

            return self::ACTIVE;
        }
        if ($result['outcome'] === 'rejected') {
            Log::info('session.ended', ['user_id' => auth()->id(), 'reason' => 'invalid_grant']);

            return self::ENDED;
        }

        Log::warning('session.refresh_failed', ['user_id' => auth()->id(), 'reason' => $result['reason']]);
        // Remember the failure; the session itself is not touched.
        Session::put(self::KEY_RETRY_AT, now()->addSeconds(self::REFRESH_RETRY_S)->timestamp);

        return self::ACTIVE;
    }

    /**
     * One refresh per refresh token at a time. Livewire sends parallel
     * requests with the same session; without the lock each would spend the
     * same refresh token, and if the realm rotates refresh tokens the second
     * one would get invalid_grant and sign the employee out. The winner's
     * result is cached briefly under the old token, so the others reuse it.
     *
     * The cached result holds live tokens and the cache store is a plain
     * database table, so only the fields store() needs are kept, and they
     * are encrypted with the app key. The key is a hash of the old token.
     *
     * @return array{outcome: 'ok'|'rejected'|'failed', tokens?: array, reason?: string}
     */
    protected function refreshOnce(string $refreshToken): array
    {
        $key = 'kc:refresh:'.hash('sha256', $refreshToken);

        try {
            return Cache::lock($key.':lock', self::REFRESH_TIMEOUT_S + 5)
                ->block(self::REFRESH_TIMEOUT_S + 2, function () use ($key, $refreshToken) {
                    $done = $this->cachedResult($key);
                    if ($done !== null) {
                        return $done;
                    }
                    $result = $this->requestRefresh($refreshToken);
                    if ($result['outcome'] !== 'failed') {
                        Cache::put($key, Crypt::encrypt(self::cacheable($result)), 60);
                    }

                    return $result;
                });
        } catch (LockTimeoutException) {
            return ['outcome' => 'failed', 'reason' => 'lock_timeout'];
        }
    }

    /** The result another request cached for this token, or null when there is none or it cannot be read. */
    protected function cachedResult(string $key): ?array
    {
        $sealed = Cache::get($key);
        if (! is_string($sealed)) {
            return null;
        }
        try {
            $result = Crypt::decrypt($sealed);
        } catch (\Throwable) {
            return null;
        }

        return is_array($result) ? $result : null;
    }

    /** Only what store() reads; Keycloak's response also carries scope, session_state and the like. */
    protected static function cacheable(array $result): array
    {
        if ($result['outcome'] !== 'ok') {
            return ['outcome' => $result['outcome']];
        }
        $tokens = array_intersect_key($result['tokens'], array_flip(['access_token', 'expires_in', 'refresh_token', 'id_token']));

        return ['outcome' => 'ok', 'tokens' => $tokens];
    }

    /** @return array{outcome: 'ok'|'rejected'|'failed', tokens?: array, reason?: string} */
    protected function requestRefresh(string $refreshToken): array
    {
        try {
            $response = Http::asForm()
                ->timeout(self::REFRESH_TIMEOUT_S)
                ->post($this->endpoint('token'), [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('services.keycloak.client_id'),
                    'client_secret' => config('services.keycloak.client_secret'),
                    'refresh_token' => $refreshToken,
                ]);
        } catch (\Throwable $e) {
            return ['outcome' => 'failed', 'reason' => class_basename($e)];
        }

        if ($response->successful() && is_string($response->json('access_token'))) {
            return ['outcome' => 'ok', 'tokens' => $response->json()];
        }
        if ($response->status() === 400 && $response->json('error') === 'invalid_grant') {
            return ['outcome' => 'rejected'];
        }

        return ['outcome' => 'failed', 'reason' => 'http_'.$response->status()];
    }

    /**
     * Revoke a refresh token at Keycloak, which ends the offline session
     * behind it. The end-session redirect alone would not: it ends the
     * browser SSO session, and an offline session outlives that. Best effort
     * and bounded to REVOKE_TIMEOUT_S, so Keluar and sign-in never hang on
     * Keycloak; a failure is logged and never blocks anything.
     */
    public function revoke(?string $refreshToken, string $event = 'logout'): void
    {
        if (! $refreshToken) {
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(self::REVOKE_TIMEOUT_S)
                ->post($this->endpoint('revoke'), [
                    'client_id' => config('services.keycloak.client_id'),
                    'client_secret' => config('services.keycloak.client_secret'),
                    'token' => $refreshToken,
                    'token_type_hint' => 'refresh_token',
                ]);
            if (! $response->successful()) {
                Log::warning($event.'.revoke_failed', ['user_id' => auth()->id(), 'reason' => 'http_'.$response->status()]);
            }
        } catch (\Throwable $e) {
            Log::warning($event.'.revoke_failed', ['user_id' => auth()->id(), 'reason' => class_basename($e)]);
        }
    }

    /**
     * True when the refresh token is an offline token. Only used to log
     * whether offline_access was granted.
     */
    public static function isOfflineToken(?string $refreshToken): bool
    {
        return (self::claims($refreshToken)['typ'] ?? null) === 'Offline';
    }

    /** The Keycloak session (`sid`, or `session_state` in older tokens) a token belongs to, or null. */
    public static function tokenSessionId(?string $token): ?string
    {
        $claims = self::claims($token);
        $sid = $claims['sid'] ?? $claims['session_state'] ?? null;

        return is_string($sid) && $sid !== '' ? $sid : null;
    }

    /**
     * What to do with the refresh token of the session a new sign-in replaces.
     *
     * - `none`: nothing to replace (no old token, or the same token).
     * - `revoke`: the old token belongs to another Keycloak session; revoke
     *   it, or that offline session stays valid for 30 days with nobody
     *   holding it.
     * - `same_session`: both tokens belong to the same Keycloak session,
     *   which happens when the SSO session was still alive at sign-in.
     *   Keycloak keeps one offline session per SSO session and client, so
     *   nothing piles up. Revoking here would be harmful: Keycloak 26 revokes
     *   per session (`sid`), so it would also end the token just issued and
     *   the employee would be signed out at the next refresh.
     * - `unknown_session`: a token without a readable `sid`. Not revoked for
     *   the same reason; the old offline session ends on its own idle limit.
     *
     * Same rule as kisara-workspace-web (session-policy.ts replacedTokenStep).
     */
    public static function replacedTokenStep(?string $previousRefreshToken, ?string $nextRefreshToken): string
    {
        if (! $previousRefreshToken || $previousRefreshToken === $nextRefreshToken) {
            return 'none';
        }
        $before = self::tokenSessionId($previousRefreshToken);
        $after = self::tokenSessionId($nextRefreshToken);
        if ($before === null || $after === null) {
            return 'unknown_session';
        }

        return $before === $after ? 'same_session' : 'revoke';
    }

    /**
     * The payload of a JWT, read WITHOUT checking the signature. Only for
     * logging and for choosing whether to revoke our own old token; never
     * base an authorization decision on it.
     */
    private static function claims(?string $token): array
    {
        $parts = explode('.', (string) $token);
        if (count($parts) !== 3) {
            return [];
        }
        $json = base64_decode(strtr($parts[1], '-_', '+/'), true);
        $payload = $json === false ? null : json_decode($json, true);

        return is_array($payload) ? $payload : [];
    }

    protected function endpoint(string $name): string
    {
        $base = rtrim((string) config('services.keycloak.base_url'), '/');
        $realm = config('services.keycloak.realms');

        return "{$base}/realms/{$realm}/protocol/openid-connect/{$name}";
    }
}
