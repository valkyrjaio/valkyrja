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

use Override;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Session\Throwable\Exception\InvalidSessionId;
use Valkyrja\Session\Throwable\Exception\SessionIdFailure;
use Valkyrja\Session\Throwable\Exception\SessionNameFailure;
use Valkyrja\Session\Throwable\Exception\SessionStartFailure;
use Valkyrja\Tests\Classes\Session\PhpSessionWithAlreadyActiveClass;
use Valkyrja\Tests\Classes\Session\PhpSessionWithFailingGetIdClass;
use Valkyrja\Tests\Classes\Session\PhpSessionWithFailingGetNameClass;
use Valkyrja\Tests\Classes\Session\PhpSessionWithFailingStartClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PHP_SESSION_ACTIVE;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class PhpSessionTest extends TestCase
{
    protected CookieParams $cookieParams;

    protected function setUp(): void
    {
        $this->cookieParams = new CookieParams(
            path: '/',
            domain: null,
            lifetime: 0,
            secure: false,
            httpOnly: false,
            sameSite: SameSite::NONE,
        );
    }

    #[Override]
    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function testImplementsSessionContract(): void
    {
        $session = new PhpSession($this->cookieParams);

        self::assertInstanceOf(SessionContract::class, $session);
    }

    public function testExtendsSession(): void
    {
        $session = new PhpSession($this->cookieParams);

        self::assertInstanceOf(Session::class, $session);
    }

    public function testDoesNotStartTwice(): void
    {
        $session = new PhpSessionWithAlreadyActiveClass($this->cookieParams);

        self::assertSame(1, $session->sessionStartCount);

        $session->start();

        self::assertSame(1, $session->sessionStartCount);
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $session = new PhpSession($this->cookieParams, 'test-session-id', 'MY_SESSION');

        self::assertSame('test-session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testSetStoresValue(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key', 'value');

        self::assertSame('value', $session->get('key'));
    }

    public function testGetReturnsDefaultForNonExistent(): void
    {
        $session = new PhpSession($this->cookieParams);

        self::assertSame('default', $session->get('nonexistent', 'default'));
    }

    public function testHasReturnsTrueForExistingItem(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key', 'value');

        self::assertTrue($session->has('key'));
    }

    public function testHasReturnsFalseForNonExistentItem(): void
    {
        $session = new PhpSession($this->cookieParams);

        self::assertFalse($session->has('nonexistent'));
    }

    public function testRemoveReturnsTrueAndRemovesItem(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key', 'value');

        self::assertTrue($session->remove('key'));
        self::assertFalse($session->has('key'));
    }

    public function testRemoveReturnsFalseForNonExistent(): void
    {
        $session = new PhpSession($this->cookieParams);

        self::assertFalse($session->remove('nonexistent'));
    }

    public function testAllReturnsAllData(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key1', 'value1');
        $session->set('key2', 'value2');

        self::assertSame(['key1' => 'value1', 'key2' => 'value2'], $session->all());
    }

    public function testClearRemovesAllData(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key1', 'value1');
        $session->set('key2', 'value2');

        $session->clear();

        self::assertSame([], $session->all());
    }

    public function testDestroyRemovesAllData(): void
    {
        $session = new PhpSession($this->cookieParams);
        $session->set('key1', 'value1');

        $session->destroy();

        self::assertSame([], $session->all());
    }

    public function testStartThrowsSessionStartFailureOnFailure(): void
    {
        $this->expectException(SessionStartFailure::class);
        $this->expectExceptionMessage('The session failed to start');

        new PhpSessionWithFailingStartClass($this->cookieParams);
    }

    public function testGetIdThrowsSessionIdFailureOnFailure(): void
    {
        $session = new PhpSessionWithFailingGetIdClass($this->cookieParams);

        $this->expectException(SessionIdFailure::class);
        $this->expectExceptionMessage('Retrieval of session id failed');

        $session->getId();
    }

    public function testGetNameThrowsSessionNameFailureOnFailure(): void
    {
        $session = new PhpSessionWithFailingGetNameClass($this->cookieParams);

        $this->expectException(SessionNameFailure::class);
        $this->expectExceptionMessage('Retrieval of session id failed');

        $session->getName();
    }

    public function testSetIdThrowsInvalidSessionIdForInvalidId(): void
    {
        $session = new PhpSession($this->cookieParams);

        $this->expectException(InvalidSessionId::class);
        $this->expectExceptionMessage("The session id, 'invalid id with spaces!', is invalid!");

        $session->setId('invalid id with spaces!');
    }

    public function testConstructorThrowsInvalidSessionIdForInvalidId(): void
    {
        $this->expectException(InvalidSessionId::class);
        $this->expectExceptionMessage("The session id, 'invalid@id#chars', is invalid!");

        new PhpSession($this->cookieParams, 'invalid@id#chars');
    }

    public function testSetIdThrowsInvalidSessionIdForTooLongId(): void
    {
        $session = new PhpSession($this->cookieParams);

        $this->expectException(InvalidSessionId::class);

        $session->setId(str_repeat('a', 129));
    }

    public function testSetIdThrowsInvalidSessionIdForEmptyId(): void
    {
        $session = new PhpSession($this->cookieParams);

        $this->expectException(InvalidSessionId::class);

        $session->setId('');
    }
}
