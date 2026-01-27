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

namespace Valkyrja\Tests\Unit\Session\Manager\Jwt;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Jwt\EncryptedJwtSession;
use Valkyrja\Session\Manager\Jwt\JwtSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class EncryptedJwtSessionTest extends TestCase
{
    protected CryptContract&MockObject $crypt;

    protected JwtContract&MockObject $jwt;

    protected ServerRequestContract&MockObject $request;

    protected EncryptedJwtSession $session;

    protected function setUp(): void
    {
        $this->crypt   = $this->createMock(CryptContract::class);
        $this->jwt     = $this->createMock(JwtContract::class);
        $this->request = $this->createMock(ServerRequestContract::class);

        $this->crypt
            ->expects($this->never())
            ->method('decrypt');

        $this->jwt
            ->expects($this->never())
            ->method('decode');

        $this->request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('');

        $this->session = new EncryptedJwtSession($this->crypt, $this->jwt, $this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testExtendsJwtSession(): void
    {
        self::assertInstanceOf(JwtSession::class, $this->session);
    }

    public function testStartDecryptsTokenBeforeJwtDecode(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Bearer encrypted-jwt-token');

        $crypt
            ->expects($this->once())
            ->method('decrypt')
            ->with('encrypted-jwt-token')
            ->willReturn('decrypted-jwt-token');

        $jwt
            ->expects($this->once())
            ->method('decode')
            ->with('decrypted-jwt-token')
            ->willReturn(['key' => 'value', 'key2' => 'value2']);

        $session = new EncryptedJwtSession($crypt, $jwt, $request);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecryptWhenHeaderIsEmpty(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new EncryptedJwtSession($crypt, $jwt, $request);

        self::assertSame([], $session->all());
    }

    public function testStartThrowsExceptionForInvalidBearerPrefix(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('Basic sometoken');

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new EncryptedJwtSession($crypt, $jwt, $request);
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $session = new EncryptedJwtSession($crypt, $jwt, $request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomHeaderName(): void
    {
        $crypt   = $this->createMock(CryptContract::class);
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $crypt
            ->expects($this->never())
            ->method('decrypt');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Custom-Auth')
            ->willReturn('');

        $session = new EncryptedJwtSession($crypt, $jwt, $request, null, null, 'X-Custom-Auth');

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
