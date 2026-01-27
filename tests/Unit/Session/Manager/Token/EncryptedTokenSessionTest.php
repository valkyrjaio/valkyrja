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

namespace Valkyrja\Tests\Unit\Session\Manager\Token;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Token\EncryptedTokenSession;
use Valkyrja\Session\Manager\Token\TokenSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class EncryptedTokenSessionTest extends TestCase
{
    protected CryptContract&MockObject $crypt;

    protected ServerRequestContract&MockObject $request;

    protected EncryptedTokenSession $session;

    protected function setUp(): void
    {
        $this->crypt   = $this->createMock(CryptContract::class);
        $this->request = $this->createMock(ServerRequestContract::class);

        $this->crypt
            ->expects($this->never())
            ->method('decrypt');

        $this->request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('');

        $this->session = new EncryptedTokenSession($this->crypt, $this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testExtendsTokenSession(): void
    {
        self::assertInstanceOf(TokenSession::class, $this->session);
    }

    public function testStartDecryptsTokenBeforeParsingData(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Bearer encrypted-token-data');

        $crypt
            ->expects($this->once())
            ->method('decrypt')
            ->with('encrypted-token-data')
            ->willReturn('{"key":"value","key2":"value2"}');

        $session = new EncryptedTokenSession($crypt, $request);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecryptWhenHeaderIsEmpty(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $session = new EncryptedTokenSession($crypt, $request);

        self::assertSame([], $session->all());
    }

    public function testStartThrowsExceptionForInvalidBearerPrefix(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('Basic sometoken');

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new EncryptedTokenSession($crypt, $request);
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $session = new EncryptedTokenSession($crypt, $request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomHeaderName(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Custom-Auth')
            ->willReturn('');

        $session = new EncryptedTokenSession($crypt, $request, null, null, 'X-Custom-Auth');

        self::assertSame('', $session->getId());
    }

    public function testSetStoresValue(): void
    {
        $this->session->set('key', 'value');

        self::assertSame('value', $this->session->get('key'));
    }

    public function testGetReturnsDefaultForNonExistent(): void
    {
        self::assertSame('default', $this->session->get('nonexistent', 'default'));
    }
}
