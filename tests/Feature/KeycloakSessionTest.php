<?php

namespace Tests\Feature;

use App\Livewire\SharedComponents\NavbarMobile;
use App\Livewire\SharedComponents\Topbar;
use App\Models\User;
use App\Services\KeycloakSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * FR-01..FR-04 of the 30-day session (docs/plan/sesi-30-hari.md in nawasara-dev).
 */
class KeycloakSessionTest extends TestCase
{
    use RefreshDatabase;

    private const BASE = 'https://sso.test';
    private const TOKEN_URL = 'https://sso.test/realms/asn-productions/protocol/openid-connect/token';
    private const REVOKE_URL = 'https://sso.test/realms/asn-productions/protocol/openid-connect/revoke';

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config([
            'services.keycloak.base_url' => self::BASE,
            'services.keycloak.realms' => 'asn-productions',
            'services.keycloak.client_id' => 'asn-portal-production',
            'services.keycloak.client_secret' => 'test-secret',
            'services.keycloak.redirect' => 'http://localhost/login/keycloak/callback',
        ]);
    }

    /** A signed-in employee whose access token expired a minute ago. */
    private function signedInWithExpiredToken(string $refreshToken = 'rt-old'): void
    {
        Auth::login(User::factory()->create());
        Session::put([
            KeycloakSession::KEY_ACCESS_TOKEN => 'at-old',
            KeycloakSession::KEY_REFRESH_TOKEN => $refreshToken,
            KeycloakSession::KEY_ID_TOKEN => 'id-old',
            KeycloakSession::KEY_EXPIRES_AT => now()->subMinute()->timestamp,
        ]);
    }

    private function tokens(): KeycloakSession
    {
        return app(KeycloakSession::class);
    }

    // ---- FR-01: refresh when near expiry ----

    public function test_a_fresh_access_token_does_not_call_keycloak(): void
    {
        Http::fake();
        $this->signedInWithExpiredToken();
        Session::put(KeycloakSession::KEY_EXPIRES_AT, now()->addMinutes(5)->timestamp);

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());
        Http::assertNothingSent();
    }

    public function test_an_expiring_access_token_is_refreshed_with_the_stored_refresh_token(): void
    {
        Http::fake([self::TOKEN_URL => Http::response([
            'access_token' => 'at-new', 'expires_in' => 300, 'refresh_token' => 'rt-new', 'refresh_expires_in' => 0,
        ])]);
        $this->signedInWithExpiredToken();

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());

        Http::assertSent(fn (Request $r) => $r->url() === self::TOKEN_URL
            && $r['grant_type'] === 'refresh_token'
            && $r['refresh_token'] === 'rt-old'
            && $r['client_id'] === 'asn-portal-production');
        $this->assertSame('at-new', Session::get(KeycloakSession::KEY_ACCESS_TOKEN));
        $this->assertSame('rt-new', Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
        // Keycloak sent no new id_token: the old one is kept for end-session.
        $this->assertSame('id-old', Session::get(KeycloakSession::KEY_ID_TOKEN));
        $this->assertGreaterThan(now()->timestamp + 200, Session::get(KeycloakSession::KEY_EXPIRES_AT));
    }

    public function test_parallel_requests_with_the_same_refresh_token_refresh_once(): void
    {
        Http::fake([self::TOKEN_URL => Http::response(['access_token' => 'at-new', 'expires_in' => 300, 'refresh_token' => 'rt-new'])]);

        $this->signedInWithExpiredToken();
        $this->tokens()->ensureFresh();
        // A second request that read the session before the first one saved it.
        $this->signedInWithExpiredToken();
        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());

        Http::assertSentCount(1);
        $this->assertSame('rt-new', Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_the_shared_refresh_result_is_cached_encrypted_and_trimmed(): void
    {
        Http::fake([self::TOKEN_URL => Http::response([
            'access_token' => 'at-new', 'expires_in' => 300, 'refresh_token' => 'rt-new',
            'scope' => 'openid offline_access', 'session_state' => 'sid-1',
        ])]);
        $this->signedInWithExpiredToken();
        $this->tokens()->ensureFresh();

        $sealed = Cache::get('kc:refresh:'.hash('sha256', 'rt-old'));
        $this->assertIsString($sealed);
        $this->assertStringNotContainsString('rt-new', $sealed);
        $this->assertStringNotContainsString('at-new', $sealed);
        $this->assertSame(
            ['outcome' => 'ok', 'tokens' => ['access_token' => 'at-new', 'expires_in' => 300, 'refresh_token' => 'rt-new']],
            Crypt::decrypt($sealed),
        );
    }

    public function test_an_unreadable_cached_result_falls_back_to_a_real_refresh(): void
    {
        Http::fake([self::TOKEN_URL => Http::response(['access_token' => 'at-new', 'expires_in' => 300, 'refresh_token' => 'rt-new'])]);
        Cache::put('kc:refresh:'.hash('sha256', 'rt-old'), 'not-encrypted', 60);
        $this->signedInWithExpiredToken();

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());
        Http::assertSentCount(1);
        $this->assertSame('rt-new', Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_session_data_is_encrypted_by_default(): void
    {
        $saved = [getenv('SESSION_ENCRYPT'), $_ENV['SESSION_ENCRYPT'] ?? null, $_SERVER['SESSION_ENCRYPT'] ?? null];
        putenv('SESSION_ENCRYPT');
        unset($_ENV['SESSION_ENCRYPT'], $_SERVER['SESSION_ENCRYPT']);
        try {
            $this->assertTrue((require base_path('config/session.php'))['encrypt']);
        } finally {
            if ($saved[0] !== false) {
                putenv('SESSION_ENCRYPT='.$saved[0]);
            }
            if ($saved[1] !== null) {
                $_ENV['SESSION_ENCRYPT'] = $saved[1];
            }
            if ($saved[2] !== null) {
                $_SERVER['SESSION_ENCRYPT'] = $saved[2];
            }
        }
    }

    public function test_the_session_lifetime_is_thirty_days_and_capped_there(): void
    {
        $this->assertSame(43200, $this->sessionLifetimeWith(null));
        $this->assertSame(43200, $this->sessionLifetimeWith('525600'));
        $this->assertSame(120, $this->sessionLifetimeWith('120'));
    }

    /** config/session.php's lifetime when SESSION_LIFETIME is $value (null: not set). */
    private function sessionLifetimeWith(?string $value): int
    {
        $saved = [getenv('SESSION_LIFETIME'), $_ENV['SESSION_LIFETIME'] ?? null, $_SERVER['SESSION_LIFETIME'] ?? null];
        try {
            if ($value === null) {
                putenv('SESSION_LIFETIME');
                unset($_ENV['SESSION_LIFETIME'], $_SERVER['SESSION_LIFETIME']);
            } else {
                putenv("SESSION_LIFETIME={$value}");
                $_ENV['SESSION_LIFETIME'] = $_SERVER['SESSION_LIFETIME'] = $value;
            }

            return (require base_path('config/session.php'))['lifetime'];
        } finally {
            $saved[0] === false ? putenv('SESSION_LIFETIME') : putenv('SESSION_LIFETIME='.$saved[0]);
            if ($saved[1] === null) {
                unset($_ENV['SESSION_LIFETIME']);
            } else {
                $_ENV['SESSION_LIFETIME'] = $saved[1];
            }
            if ($saved[2] === null) {
                unset($_SERVER['SESSION_LIFETIME']);
            } else {
                $_SERVER['SESSION_LIFETIME'] = $saved[2];
            }
        }
    }

    // ---- FR-02: only invalid_grant ends the session ----

    public function test_invalid_grant_signs_the_employee_out(): void
    {
        Http::fake([self::TOKEN_URL => Http::response(['error' => 'invalid_grant', 'error_description' => 'Offline user session not found'], 400)]);
        $this->signedInWithExpiredToken();

        $component = new Topbar();
        $this->assertSame(['active' => false], $component->checkKeycloakSession());

        $this->assertFalse(Auth::check());
        $this->assertNull(Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_signing_out_destroys_the_old_session_record(): void
    {
        // A cookie copied from a shared computer must not work after Keluar.
        $this->signedInWithExpiredToken();
        Session::save();
        $oldId = Session::getId();
        $handler = Session::getHandler();
        $this->assertNotSame('', $handler->read($oldId));

        (new Topbar())->logoutLaravel();

        $this->assertSame('', $handler->read($oldId));
        $this->assertNotSame($oldId, Session::getId());
        $this->assertFalse(Auth::check());
    }

    public function test_invalid_grant_destroys_the_old_session_record(): void
    {
        Http::fake([self::TOKEN_URL => Http::response(['error' => 'invalid_grant'], 400)]);
        $this->signedInWithExpiredToken();
        Session::save();
        $oldId = Session::getId();

        (new Topbar())->checkKeycloakSession();

        $this->assertSame('', Session::getHandler()->read($oldId));
    }

    public function test_a_keycloak_outage_keeps_the_employee_signed_in_and_retries_later(): void
    {
        Http::fake([self::TOKEN_URL => Http::response('Service Unavailable', 503)]);
        $this->signedInWithExpiredToken();
        Log::spy();

        $this->assertSame(['active' => true], (new Topbar())->checkKeycloakSession());
        $this->assertTrue(Auth::check());
        $this->assertSame('rt-old', Session::get(KeycloakSession::KEY_REFRESH_TOKEN));

        // Within the retry window Keycloak is left alone.
        $this->tokens()->ensureFresh();
        Http::assertSentCount(1);

        // After it, the refresh is tried again.
        $this->travel(KeycloakSession::REFRESH_RETRY_S + 1)->seconds();
        $this->tokens()->ensureFresh();
        Http::assertSentCount(2);

        // An outage is a warning, not an error.
        Log::shouldHaveReceived('log')->with('warning', 'session.refresh_failed', \Mockery::any());
        Log::shouldNotHaveReceived('log', ['error', 'session.refresh_failed', \Mockery::any()]);
    }

    public function test_a_network_error_keeps_the_employee_signed_in(): void
    {
        Http::fake(fn () => throw new ConnectionException('Connection refused'));
        $this->signedInWithExpiredToken();

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());
        $this->assertTrue(Auth::check());
        $this->assertNotNull(Session::get(KeycloakSession::KEY_RETRY_AT));
    }

    public function test_a_broken_cache_store_keeps_the_employee_signed_in(): void
    {
        Http::fake();
        $this->signedInWithExpiredToken();
        Cache::shouldReceive('lock')->andThrow(new \RuntimeException('cache table unreachable'));

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());
        $this->assertTrue(Auth::check());
        $this->assertNotNull(Session::get(KeycloakSession::KEY_RETRY_AT));
        Http::assertNothingSent();
    }

    public function test_other_oauth_errors_do_not_sign_the_employee_out(): void
    {
        Http::fake([self::TOKEN_URL => Http::response(['error' => 'invalid_client'], 401)]);
        $this->signedInWithExpiredToken();
        Log::spy();

        $this->assertSame(KeycloakSession::ACTIVE, $this->tokens()->ensureFresh());
        $this->assertTrue(Auth::check());
        // A rotated client secret must surface: logged as an error, not a warning.
        Log::shouldHaveReceived('log')->with('error', 'session.refresh_failed', \Mockery::on(
            fn ($context) => $context['reason'] === 'http_401_invalid_client'
        ));
    }

    public function test_pages_render_for_signed_in_employees_while_keycloak_is_down(): void
    {
        // Every Keycloak call fails, including the admin API the top bar
        // used to call on each page for a number it never displayed.
        Http::fake(fn () => throw new ConnectionException('Connection refused'));
        $this->signedInWithExpiredToken();

        Livewire::test(Topbar::class)->assertOk()->assertNotDispatched('set-modal-session');
        Livewire::test(NavbarMobile::class)->assertOk()->assertNotDispatched('set-modal-session');

        $this->assertTrue(Auth::check());
    }

    public function test_a_session_without_a_refresh_token_is_ended(): void
    {
        Http::fake();
        Auth::login(User::factory()->create());

        $this->assertSame(['active' => false], (new Topbar())->checkKeycloakSession());
        $this->assertFalse(Auth::check());
        Http::assertNothingSent();
    }

    // ---- FR-03: Keluar revokes, then end-session, then clears the session ----

    public function test_logout_revokes_the_refresh_token_and_ends_the_keycloak_session(): void
    {
        Http::fake([self::REVOKE_URL => Http::response('', 200)]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([
                KeycloakSession::KEY_REFRESH_TOKEN => 'rt-current',
                KeycloakSession::KEY_ID_TOKEN => 'id-current',
            ])
            ->post('/logout');

        Http::assertSent(fn (Request $r) => $r->url() === self::REVOKE_URL
            && $r['token'] === 'rt-current'
            && $r['token_type_hint'] === 'refresh_token'
            && $r['client_id'] === 'asn-portal-production');

        $location = $response->headers->get('Location');
        $this->assertStringStartsWith(self::BASE.'/realms/asn-productions/protocol/openid-connect/logout?', $location);
        $this->assertStringContainsString('id_token_hint=id-current', $location);
        $this->assertGuest();
        $this->assertNull(Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_logout_still_signs_out_when_revocation_fails(): void
    {
        Http::fake(fn () => throw new ConnectionException('timed out'));
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([
                KeycloakSession::KEY_REFRESH_TOKEN => 'rt-current',
                KeycloakSession::KEY_ID_TOKEN => 'id-current',
            ])
            ->post('/logout');

        $response->assertRedirect();
        $this->assertStringContainsString('/protocol/openid-connect/logout?', $response->headers->get('Location'));
        $this->assertGuest();
    }

    // ---- FR-01 / FR-04: the first round does NOT ask for offline_access (BUG-001), revokes the replaced token, no remember-me ----

    public function test_sign_in_does_not_ask_for_offline_access(): void
    {
        // An offline code exchanged by the first app of an SSO session makes
        // Keycloak 26 drop the online session: every other app then asks for
        // the password again (KeycloakSession::SCOPES).
        $location = $this->get('/login')->headers->get('Location');

        $this->assertStringStartsWith(self::BASE.'/realms/asn-productions/protocol/openid-connect/auth?', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $query);
        $this->assertNotContains('offline_access', explode(' ', $query['scope']));
        $this->assertContains('openid', explode(' ', $query['scope']));
    }

    public function test_a_new_sign_in_revokes_the_replaced_token_of_another_session_and_sets_no_remember_cookie(): void
    {
        Http::fake([self::REVOKE_URL => Http::response('', 200)]);
        $fresh = $this->fakeKeycloakUser('sid-new');
        $previous = self::jwt(['typ' => 'Offline', 'sid' => 'sid-old']);

        $response = $this->withSession([KeycloakSession::KEY_REFRESH_TOKEN => $previous])
            ->get('/login/keycloak/callback?code=x&state=y');

        $response->assertRedirect('/login/offline');
        Http::assertSent(fn (Request $r) => $r->url() === self::REVOKE_URL && $r['token'] === $previous);
        Http::assertSentCount(1);
        $this->assertAuthenticated();
        $response->assertCookieMissing(Auth::guard()->getRecallerName());
        $this->assertSame($fresh, Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
        $this->assertSame('id-fresh', Session::get(KeycloakSession::KEY_ID_TOKEN));
        $this->assertSame('kc-sub-1', Session::get(KeycloakSession::KEY_USER_ID));
    }

    public function test_a_replaced_token_of_the_same_keycloak_session_is_not_revoked(): void
    {
        // Keycloak 26 revokes per session: this would also end the new token.
        Http::fake();
        $this->fakeKeycloakUser('sid-same');

        $this->withSession([KeycloakSession::KEY_REFRESH_TOKEN => self::jwt(['typ' => 'Offline', 'sid' => 'sid-same'])])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/login/offline');

        Http::assertNothingSent();
        $this->assertAuthenticated();
    }

    public function test_a_replaced_token_without_a_readable_session_is_not_revoked(): void
    {
        Http::fake();
        $this->fakeKeycloakUser('sid-new');

        $this->withSession([KeycloakSession::KEY_REFRESH_TOKEN => 'opaque-token'])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/login/offline');

        Http::assertNothingSent();
    }

    public function test_a_failed_revocation_never_blocks_sign_in(): void
    {
        Http::fake([self::REVOKE_URL => Http::response('', 503)]);
        $fresh = $this->fakeKeycloakUser('sid-new');

        $this->withSession([KeycloakSession::KEY_REFRESH_TOKEN => self::jwt(['sid' => 'sid-old'])])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/login/offline');

        $this->assertAuthenticated();
        $this->assertSame($fresh, Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_a_first_sign_in_has_nothing_to_revoke(): void
    {
        Http::fake();
        $this->fakeKeycloakUser('sid-new');

        $this->get('/login/keycloak/callback?code=x&state=y')->assertRedirect('/login/offline');

        Http::assertNothingSent();
        $this->assertAuthenticated();
    }

    // ---- BUG-001 fix B: online first round, then a silent prompt=none round for the offline token ----

    public function test_the_first_round_signs_in_online_and_queues_the_silent_round(): void
    {
        Http::fake();
        $this->fakeKeycloakUser('sid-new');

        $this->get('/login/keycloak/callback?code=x&state=y')->assertRedirect('/login/offline');

        $this->assertAuthenticated();
        $this->assertIsInt(Session::get(KeycloakSession::KEY_OFFLINE_PENDING));
    }

    public function test_the_silent_round_asks_for_offline_access_with_prompt_none(): void
    {
        $this->actingAs(User::factory()->create());

        $location = $this->withSession([KeycloakSession::KEY_OFFLINE_PENDING => now()->timestamp])
            ->get('/login/offline')->headers->get('Location');

        $this->assertStringStartsWith(self::BASE.'/realms/asn-productions/protocol/openid-connect/auth?', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $query);
        $this->assertSame('none', $query['prompt']);
        $this->assertContains('offline_access', explode(' ', $query['scope']));
        $this->assertContains('openid', explode(' ', $query['scope']));
        $this->assertNull(Session::get(KeycloakSession::KEY_OFFLINE_PENDING));
        $this->assertIsInt(Session::get(KeycloakSession::KEY_OFFLINE_ROUND));
    }

    public function test_the_silent_round_runs_only_once_right_after_a_sign_in(): void
    {
        // No pending flag (a bookmark, a second visit): nothing to do.
        $this->actingAs(User::factory()->create());
        $this->get('/login/offline')->assertRedirect('/');

        // A stale flag (round abandoned) is ignored too.
        $this->withSession([KeycloakSession::KEY_OFFLINE_PENDING => now()->timestamp - KeycloakSession::OFFLINE_ROUND_TTL_S - 1])
            ->get('/login/offline')->assertRedirect('/');
        $this->assertNull(Session::get(KeycloakSession::KEY_OFFLINE_ROUND));
    }

    public function test_a_guest_cannot_start_the_silent_round(): void
    {
        $this->withSession([KeycloakSession::KEY_OFFLINE_PENDING => now()->timestamp])
            ->get('/login/offline')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_the_silent_round_swaps_in_the_offline_token_without_revoking(): void
    {
        Http::fake();
        $offline = $this->fakeKeycloakUser('sid-same');
        $this->actingAs(User::factory()->create());

        $this->withSession($this->onlineSession() + [KeycloakSession::KEY_OFFLINE_ROUND => now()->timestamp])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/');

        // Revoking the online token would end the offline one too (same sid).
        Http::assertNothingSent();
        $this->assertAuthenticated();
        $this->assertSame($offline, Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
        $this->assertNull(Session::get(KeycloakSession::KEY_OFFLINE_ROUND));
    }

    public function test_a_refused_silent_round_keeps_the_online_sign_in(): void
    {
        Http::fake();
        $this->actingAs(User::factory()->create());
        $session = $this->onlineSession();

        $this->withSession($session + [KeycloakSession::KEY_OFFLINE_ROUND => now()->timestamp])
            ->get('/login/keycloak/callback?error=login_required&state=y')
            ->assertRedirect('/');

        $this->assertAuthenticated();
        $this->assertSame($session[KeycloakSession::KEY_REFRESH_TOKEN], Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_a_failing_code_exchange_in_the_silent_round_keeps_the_online_sign_in(): void
    {
        Socialite::shouldReceive('driver->user')->andThrow(new \Laravel\Socialite\Two\InvalidStateException());
        $this->actingAs(User::factory()->create());
        $session = $this->onlineSession();

        $this->withSession($session + [KeycloakSession::KEY_OFFLINE_ROUND => now()->timestamp])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/');

        $this->assertAuthenticated();
        $this->assertSame($session[KeycloakSession::KEY_REFRESH_TOKEN], Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_another_account_answering_the_silent_round_is_ignored(): void
    {
        Http::fake();
        $this->fakeKeycloakUser('sid-other');
        $this->actingAs(User::factory()->create());
        $session = [KeycloakSession::KEY_USER_ID => 'kc-sub-someone-else'] + $this->onlineSession();

        $this->withSession($session + [KeycloakSession::KEY_OFFLINE_ROUND => now()->timestamp])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/');

        $this->assertSame($session[KeycloakSession::KEY_REFRESH_TOKEN], Session::get(KeycloakSession::KEY_REFRESH_TOKEN));
    }

    public function test_a_stale_round_flag_makes_the_callback_a_normal_sign_in(): void
    {
        Http::fake();
        $this->fakeKeycloakUser('sid-new');

        $this->withSession([KeycloakSession::KEY_OFFLINE_ROUND => now()->timestamp - KeycloakSession::OFFLINE_ROUND_TTL_S - 1])
            ->get('/login/keycloak/callback?code=x&state=y')
            ->assertRedirect('/login/offline');
    }

    /** Session of an employee signed in on round 1's online token. */
    private function onlineSession(): array
    {
        return [
            KeycloakSession::KEY_USER_ID => 'kc-sub-1',
            KeycloakSession::KEY_REFRESH_TOKEN => self::jwt(['typ' => 'Refresh', 'sid' => 'sid-same']),
            KeycloakSession::KEY_ACCESS_TOKEN => 'at-online',
            KeycloakSession::KEY_EXPIRES_AT => now()->addMinutes(5)->timestamp,
        ];
    }

    /** An unsigned JWT-shaped token: only the payload matters to the code under test. */
    private static function jwt(array $payload): string
    {
        return 'h.'.rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=').'.s';
    }

    /** Fakes the Socialite callback; returns the new refresh token. */
    private function fakeKeycloakUser(string $sid): string
    {
        $refreshToken = self::jwt(['typ' => 'Offline', 'sid' => $sid]);
        $user = (new SocialiteUser())
            ->map(['id' => 'kc-sub-1', 'name' => 'Pegawai Uji', 'email' => 'pegawai@ponorogo.go.id']);
        $user->accessTokenResponseBody = [
            'access_token' => 'at-fresh',
            'expires_in' => 300,
            'refresh_token' => $refreshToken,
            'id_token' => 'id-fresh',
        ];

        Socialite::shouldReceive('driver->user')->andReturn($user);

        return $refreshToken;
    }
}
