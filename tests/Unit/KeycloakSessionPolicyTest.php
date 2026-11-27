<?php

namespace Tests\Unit;

use App\Services\KeycloakSession;
use PHPUnit\Framework\TestCase;

class KeycloakSessionPolicyTest extends TestCase
{
    private const NOW = 1_800_000_000;

    public function test_a_token_well_before_expiry_is_used(): void
    {
        $this->assertSame('use', KeycloakSession::refreshStep(self::NOW + 300, null, self::NOW));
    }

    public function test_a_token_inside_the_margin_is_refreshed(): void
    {
        $this->assertSame('refresh', KeycloakSession::refreshStep(self::NOW + KeycloakSession::REFRESH_MARGIN_S, null, self::NOW));
        $this->assertSame('refresh', KeycloakSession::refreshStep(self::NOW - 10, null, self::NOW));
    }

    public function test_a_session_without_an_expiry_is_refreshed(): void
    {
        $this->assertSame('refresh', KeycloakSession::refreshStep(null, null, self::NOW));
    }

    public function test_a_recent_failure_waits_instead_of_retrying_on_every_request(): void
    {
        $this->assertSame('wait', KeycloakSession::refreshStep(self::NOW - 10, self::NOW + 30, self::NOW));
        $this->assertSame('refresh', KeycloakSession::refreshStep(self::NOW - 10, self::NOW, self::NOW));
    }

    public function test_offline_tokens_are_recognised_from_their_payload(): void
    {
        $token = fn (array $payload) => 'h.'.rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=').'.s';

        $this->assertTrue(KeycloakSession::isOfflineToken($token(['typ' => 'Offline'])));
        $this->assertFalse(KeycloakSession::isOfflineToken($token(['typ' => 'Refresh'])));
        $this->assertFalse(KeycloakSession::isOfflineToken('not-a-jwt'));
        $this->assertFalse(KeycloakSession::isOfflineToken(null));
    }

    private static function jwt(array $payload): string
    {
        return 'h.'.rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=').'.s';
    }

    public function test_the_session_id_comes_from_sid_or_session_state(): void
    {
        $this->assertSame('a', KeycloakSession::tokenSessionId(self::jwt(['sid' => 'a'])));
        $this->assertSame('b', KeycloakSession::tokenSessionId(self::jwt(['session_state' => 'b'])));
        $this->assertNull(KeycloakSession::tokenSessionId(self::jwt(['sid' => ''])));
        $this->assertNull(KeycloakSession::tokenSessionId('opaque'));
        $this->assertNull(KeycloakSession::tokenSessionId(null));
    }

    public function test_only_a_token_of_another_keycloak_session_is_revoked_on_sign_in(): void
    {
        $old = self::jwt(['sid' => 'one']);

        $this->assertSame('none', KeycloakSession::replacedTokenStep(null, $old));
        $this->assertSame('none', KeycloakSession::replacedTokenStep($old, $old));
        $this->assertSame('revoke', KeycloakSession::replacedTokenStep($old, self::jwt(['sid' => 'two'])));
        $this->assertSame('same_session', KeycloakSession::replacedTokenStep($old, self::jwt(['sid' => 'one', 'jti' => 'x'])));
        $this->assertSame('unknown_session', KeycloakSession::replacedTokenStep('opaque', self::jwt(['sid' => 'two'])));
        $this->assertSame('unknown_session', KeycloakSession::replacedTokenStep($old, null));
    }
}
