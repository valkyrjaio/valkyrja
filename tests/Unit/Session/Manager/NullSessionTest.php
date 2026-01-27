<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Session\Manager;

use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Throwable\Exception\InvalidCsrfToken;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function strlen;

class NullSessionTest extends TestCase
{
    protected NullSession $session;

    protected function setUp(): void
    {
        $this->session = new NullSession();
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testGetIdReturnsEmptyStringByDefault(): void
    {
        self::assertSame('', $this->session->getId());
    }

    public function testSetIdSetsTheId(): void
    {
        $this->session->setId('test-session-id');

        self::assertSame('test-session-id', $this->session->getId());
    }

    public function testSetIdAllowsAlphanumericDashesAndCommas(): void
    {
        $this->session->setId('valid-session-id,123');

        self::assertSame('valid-session-id,123', $this->session->getId());
    }

    public function testSetIdAcceptsMaxLengthId(): void
    {
        $maxLengthId = str_repeat('a', 128);
        $this->session->setId($maxLengthId);

        self::assertSame($maxLengthId, $this->session->getId());
    }

    public function testConstructorWithSessionId(): void
    {
        $session = new NullSession('my-session-id');

        self::assertSame('my-session-id', $session->getId());
    }

    public function testGetNameReturnsEmptyStringByDefault(): void
    {
        self::assertSame('', $this->session->getName());
    }

    public function testSetNameSetsTheName(): void
    {
        $this->session->setName('PHPSESSID');

        self::assertSame('PHPSESSID', $this->session->getName());
    }

    public function testConstructorWithSessionName(): void
    {
        $session = new NullSession(null, 'MY_SESSION');

        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithBothIdAndName(): void
    {
        $session = new NullSession('session-id-123', 'MY_SESSION');

        self::assertSame('session-id-123', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testIsActiveReturnsTrue(): void
    {
        self::assertTrue($this->session->isActive());
    }

    public function testHasReturnsFalseForNonExistentItem(): void
    {
        self::assertFalse($this->session->has('nonexistent'));
    }

    public function testHasReturnsTrueForExistingItem(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->has('key'));
    }

    public function testGetReturnsNullForNonExistentItem(): void
    {
        self::assertNull($this->session->get('nonexistent'));
    }

    public function testGetReturnsDefaultForNonExistentItem(): void
    {
        self::assertSame('default', $this->session->get('nonexistent', 'default'));
    }

    public function testGetReturnsStoredValue(): void
    {
        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testSetStoresValue(): void
    {
        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testSetOverwritesExistingValue(): void
    {
        $this->session->set('key', 'original');
        $this->session->set('key', 'updated');

        self::assertSame('updated', $this->session->get('key'));
    }

    public function testSetStoresArrayValue(): void
    {
        $value = ['nested' => ['data' => 'value']];
        $this->session->set('key', $value);

        self::assertSame($value, $this->session->get('key'));
    }

    public function testRemoveReturnsFalseForNonExistentItem(): void
    {
        self::assertFalse($this->session->remove('nonexistent'));
    }

    public function testRemoveReturnsTrueAndRemovesItem(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->remove('key'));
        self::assertFalse($this->session->has('key'));
    }

    public function testAllReturnsEmptyArrayByDefault(): void
    {
        self::assertSame([], $this->session->all());
    }

    public function testAllReturnsAllStoredData(): void
    {
        $this->session->set('key1', 'value1');
        $this->session->set('key2', 'value2');

        self::assertSame(['key1' => 'value1', 'key2' => 'value2'], $this->session->all());
    }

    public function testGenerateCsrfTokenGeneratesUniqueToken(): void
    {
        $token1 = $this->session->generateCsrfToken('csrf1');
        $token2 = $this->session->generateCsrfToken('csrf2');

        self::assertNotEmpty($token1);
        self::assertNotEmpty($token2);
        self::assertNotSame($token1, $token2);
    }

    public function testGenerateCsrfTokenStoresTokenInSession(): void
    {
        $token = $this->session->generateCsrfToken('csrf_id');

        self::assertTrue($this->session->has('csrf_id'));
        self::assertSame($token, $this->session->get('csrf_id'));
    }

    public function testGenerateCsrfTokenReturns128CharacterHexString(): void
    {
        $token = $this->session->generateCsrfToken('csrf');

        // 64 bytes = 128 hex characters
        self::assertSame(128, strlen($token));
        self::assertMatchesRegularExpression('/^[a-f0-9]+$/', $token);
    }

    public function testValidateCsrfTokenDoesNotThrowForValidToken(): void
    {
        $token = $this->session->generateCsrfToken('csrf');

        $this->session->validateCsrfToken('csrf', $token);

        // Token should be consumed after validation
        self::assertFalse($this->session->has('csrf'));
    }

    public function testValidateCsrfTokenThrowsForInvalidToken(): void
    {
        $this->session->generateCsrfToken('csrf');

        $this->expectException(InvalidCsrfToken::class);
        $this->expectExceptionMessage('CSRF token id: `csrf` has invalid token of `wrong-token` provided');

        $this->session->validateCsrfToken('csrf', 'wrong-token');
    }

    public function testValidateCsrfTokenThrowsForNonExistentId(): void
    {
        $this->expectException(InvalidCsrfToken::class);

        $this->session->validateCsrfToken('nonexistent', 'any-token');
    }

    public function testIsCsrfTokenValidReturnsTrueForValidToken(): void
    {
        $token = $this->session->generateCsrfToken('csrf');

        self::assertTrue($this->session->isCsrfTokenValid('csrf', $token));
    }

    public function testIsCsrfTokenValidReturnsFalseForInvalidToken(): void
    {
        $this->session->generateCsrfToken('csrf');

        self::assertFalse($this->session->isCsrfTokenValid('csrf', 'wrong-token'));
    }

    public function testIsCsrfTokenValidReturnsFalseForNonExistentId(): void
    {
        self::assertFalse($this->session->isCsrfTokenValid('nonexistent', 'any-token'));
    }

    public function testIsCsrfTokenValidConsumesTokenOnSuccess(): void
    {
        $token = $this->session->generateCsrfToken('csrf');

        self::assertTrue($this->session->isCsrfTokenValid('csrf', $token));
        // Token should be removed after successful validation
        self::assertFalse($this->session->has('csrf'));
    }

    public function testIsCsrfTokenValidDoesNotConsumeTokenOnFailure(): void
    {
        $this->session->generateCsrfToken('csrf');

        self::assertFalse($this->session->isCsrfTokenValid('csrf', 'wrong-token'));
        // Token should still exist after failed validation
        self::assertTrue($this->session->has('csrf'));
    }

    public function testIsCsrfTokenValidReturnsFalseForNonStringSessionToken(): void
    {
        // Manually set a non-string token
        $this->session->set('csrf', 12345);

        self::assertFalse($this->session->isCsrfTokenValid('csrf', '12345'));
    }

    public function testClearRemovesAllData(): void
    {
        $this->session->set('key1', 'value1');
        $this->session->set('key2', 'value2');

        $this->session->clear();

        self::assertSame([], $this->session->all());
    }

    public function testDestroyRemovesAllData(): void
    {
        $this->session->set('key1', 'value1');
        $this->session->set('key2', 'value2');

        $this->session->destroy();

        self::assertSame([], $this->session->all());
    }

    public function testStartDoesNothing(): void
    {
        // NullSession start() is a no-op
        $this->session->set('key', 'value');

        $this->session->start();

        self::assertSame('value', $this->session->get('key'));
    }
}
