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

namespace Valkyrja\Tests\Unit\Session\Manager\Cookie;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Cookie\CookieSession;
use Valkyrja\Session\Manager\Cookie\EncryptedCookieSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class EncryptedCookieSessionTest extends TestCase
{
    protected CryptContract&MockObject $crypt;

    protected ServerRequestContract&MockObject $request;

    protected EncryptedCookieSession $session;

    protected function setUp(): void
    {
        $this->crypt   = $this->createMock(CryptContract::class);
        $this->request = $this->createMock(ServerRequestContract::class);

        $this->crypt
            ->expects($this->never())
            ->method('decryptArray');

        $this->request
            ->expects($this->once())
            ->method('getCookieParam')
            ->with('VALKYRJA_SESSION')
            ->willReturn(null);

        $this->session = new EncryptedCookieSession($this->crypt, $this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testExtendsCookieSession(): void
    {
        self::assertInstanceOf(CookieSession::class, $this->session);
    }

    public function testStartDecryptsDataFromCookie(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getCookieParam')
            ->with('session-id')
            ->willReturn('encrypted-cookie-data');

        $crypt
            ->expects($this->once())
            ->method('decryptArray')
            ->with('encrypted-cookie-data')
            ->willReturn(['key' => 'value', 'key2' => 'value2']);

        $session = new EncryptedCookieSession($crypt, $request, 'session-id');

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecryptWhenCookieIsNull(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getCookieParam')
            ->with('session-id')
            ->willReturn(null);

        $crypt
            ->expects($this->never())
            ->method('decryptArray');

        $session = new EncryptedCookieSession($crypt, $request, 'session-id');

        self::assertSame([], $session->all());
    }

    public function testStartDoesNotDecryptWhenCookieIsEmpty(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getCookieParam')
            ->with('session-id')
            ->willReturn('');

        $crypt
            ->expects($this->never())
            ->method('decryptArray');

        $session = new EncryptedCookieSession($crypt, $request, 'session-id');

        self::assertSame([], $session->all());
    }

    public function testSetStoresValue(): void
    {
        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testRemoveReturnsTrueWhenItemExists(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->remove('key'));
        self::assertFalse($this->session->has('key'));
    }

    public function testRemoveReturnsFalseWhenItemDoesNotExist(): void
    {
        self::assertFalse($this->session->remove('nonexistent'));
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

    public function testConstructorWithSessionName(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $crypt
            ->expects($this->never())
            ->method('decryptArray');

        $request
            ->expects($this->once())
            ->method('getCookieParam')
            ->willReturn(null);

        $session = new EncryptedCookieSession($crypt, $request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testGetDataAsCookieValueEncryptsData(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getCookieParam')
            ->willReturn(null);

        $crypt
            ->expects($this->once())
            ->method('encryptArray')
            ->with(['key' => 'value'])
            ->willReturn('encrypted-data');

        $session = new EncryptedCookieSession($crypt, $request, 'session-id');

        // Trigger the encryption via set which calls updateCookieSession
        // Note: setcookie() won't work in CLI, but encryptArray should still be called
        $session->set('key', 'value');
    }
}
