<?php

namespace Tests\Feature;

use App\Livewire\SharedComponents\PasskeyBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class PasskeyBannerTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN_URL = 'https://sso.test/realms/asn-productions/protocol/openid-connect/token';
    private const CREDENTIALS_URL = 'https://sso.test/admin/realms/asn-productions/users/kc-sub-1/credentials';

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config([
            'services.keycloak.base_url' => 'https://sso.test',
            'services.keycloak.realms' => 'asn-productions',
            'services.keycloak.client_id' => 'asn-portal-production',
            'services.keycloak.client_secret' => 'test-secret',
        ]);
        $this->actingAs(User::factory()->create());
        Session::put('keycloak_id_user', 'kc-sub-1');
    }

    private function keycloakCredentials(array $credentials): void
    {
        Http::fake([
            self::TOKEN_URL => Http::response(['access_token' => 'admin-token']),
            self::CREDENTIALS_URL => Http::response($credentials),
        ]);
    }

    private function credentialRequests(): int
    {
        return Http::recorded(fn ($r) => $r->url() === self::CREDENTIALS_URL)->count();
    }

    public function test_the_banner_shows_without_a_passkey_and_keycloak_is_asked_once(): void
    {
        $this->keycloakCredentials([['type' => 'password']]);

        Livewire::test(PasskeyBanner::class)->assertSet('show', true);
        Livewire::test(PasskeyBanner::class)->assertSet('show', true);

        $this->assertSame(1, $this->credentialRequests());
    }

    public function test_the_banner_hides_with_a_passkey(): void
    {
        $this->keycloakCredentials([['type' => 'password'], ['type' => 'webauthn-passwordless']]);

        Livewire::test(PasskeyBanner::class)->assertSet('show', false);
        Livewire::test(PasskeyBanner::class)->assertSet('show', false);

        $this->assertSame(1, $this->credentialRequests());
    }

    public function test_the_missing_status_expires_so_a_new_passkey_hides_the_banner(): void
    {
        // Http::fake() stubs cannot be replaced mid-test, so the answers come in order.
        $answers = [[['type' => 'password']], [['type' => 'webauthn-passwordless']]];
        Http::fake(function ($request) use (&$answers) {
            return $request->url() === self::TOKEN_URL
                ? Http::response(['access_token' => 'admin-token'])
                : Http::response(array_shift($answers));
        });

        Livewire::test(PasskeyBanner::class)->assertSet('show', true);
        $this->travel(PasskeyBanner::PASSKEY_MISSING_TTL + 1)->seconds();
        Livewire::test(PasskeyBanner::class)->assertSet('show', false);
    }

    public function test_a_keycloak_outage_hides_the_banner_and_is_not_cached(): void
    {
        $down = true;
        Http::fake(function ($request) use (&$down) {
            if ($down) {
                throw new ConnectionException('Connection refused');
            }

            return $request->url() === self::TOKEN_URL
                ? Http::response(['access_token' => 'admin-token'])
                : Http::response([['type' => 'password']]);
        });

        Livewire::test(PasskeyBanner::class)->assertOk()->assertSet('show', false);

        $down = false;
        Livewire::test(PasskeyBanner::class)->assertSet('show', true);
    }

    public function test_a_keycloak_error_response_hides_the_banner(): void
    {
        Http::fake([
            self::TOKEN_URL => Http::response(['access_token' => 'admin-token']),
            self::CREDENTIALS_URL => Http::response('', 503),
        ]);

        Livewire::test(PasskeyBanner::class)->assertOk()->assertSet('show', false);
    }
}
