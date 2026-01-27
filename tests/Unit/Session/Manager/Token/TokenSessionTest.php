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
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Token\TokenSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class TokenSessionTest extends TestCase
{
    protected ServerRequestContract&MockObject $request;

    protected TokenSession $session;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestContract::class);

        $this->request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('');

        $this->session = new TokenSession($this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartLoadsDataFromBearerToken(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Bearer {"key":"value","key2":"value2"}');

        $session = new TokenSession($request);

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotLoadDataWhenHeaderIsEmpty(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $session = new TokenSession($request);

        self::assertSame([], $session->all());
    }

    public function testStartThrowsExceptionForInvalidBearerPrefix(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Basic sometoken');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new TokenSession($request);
    }

    public function testStartThrowsExceptionForEmptyToken(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with(HeaderName::AUTHORIZATION)
            ->willReturn('Bearer ');

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Invalid authorization header');

        new TokenSession($request);
    }

    public function testConstructorWithSessionIdAndName(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('');

        $session = new TokenSession($request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }

    public function testConstructorWithCustomHeaderName(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Custom-Auth')
            ->willReturn('');

        $session = new TokenSession($request, null, null, 'X-Custom-Auth');

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
}
