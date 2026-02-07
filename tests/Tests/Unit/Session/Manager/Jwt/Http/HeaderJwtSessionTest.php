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

namespace Valkyrja\Tests\Unit\Session\Manager\Jwt\Http;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Jwt\Http\HeaderJwtSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class HeaderJwtSessionTest extends TestCase
{
    protected JwtContract&MockObject $jwt;

    protected ServerRequestContract&MockObject $request;

    protected HeaderJwtSession $session;

    protected function setUp(): void
    {
        $this->jwt     = $this->createMock(JwtContract::class);
        $this->request = $this->createMock(ServerRequestContract::class);

        $this->jwt
            ->expects($this->never())
            ->method('decode');

        $this->request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('');

        $this->session = new HeaderJwtSession($this->jwt, $this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartDecodesJwtToken(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.test');

        $jwt
            ->expects($this->once())
            ->method('decode')
            ->with('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.test')
            ->willReturn(['key' => 'value', 'key2' => 'value2']);

        $session = new HeaderJwtSession($jwt, $request);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotDecodeWhenHeaderIsEmpty(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $session = new HeaderJwtSession($jwt, $request);

        self::assertSame([], $session->all());
    }

    public function testStartThrowsExceptionForInvalidBearerPrefix(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('Basic sometoken');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new HeaderJwtSession($jwt, $request);
    }

    public function testStartThrowsExceptionForEmptyToken(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('Bearer ');

        $jwt
            ->expects($this->never())
            ->method('decode');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new HeaderJwtSession($jwt, $request);
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $session = new HeaderJwtSession($jwt, $request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomHeaderName(): void
    {
        $jwt     = $this->createMock(JwtContract::class);
        $request = $this->createMock(ServerRequestContract::class);

        $jwt
            ->expects($this->never())
            ->method('decode');

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Custom-Auth')
            ->willReturn('');

        $session = new HeaderJwtSession($jwt, $request, null, null, 'X-Custom-Auth');

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

    public function testHasReturnsTrueForExistingItem(): void
    {
        $this->session->set('key', 'value');

        self::assertTrue($this->session->has('key'));
    }

    public function testHasReturnsFalseForNonExistentItem(): void
    {
        self::assertFalse($this->session->has('nonexistent'));
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
}
