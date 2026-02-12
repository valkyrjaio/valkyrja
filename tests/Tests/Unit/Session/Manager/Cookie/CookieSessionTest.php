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
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Cookie\CookieSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CookieSessionTest extends TestCase
{
    protected ServerRequestContract&MockObject $request;

    protected CookieSession $session;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->with('VALKYRJA_SESSION')
            ->willReturn(null);

        $this->request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $this->session = new CookieSession($this->request);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartLoadsDataFromCookie(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->with('session-id')
            ->willReturn('{"key":"value","key2":"value2"}');

        $request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $session = new CookieSession($request, 'session-id');

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotLoadDataWhenCookieIsNull(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->with('session-id')
            ->willReturn(null);

        $request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $session = new CookieSession($request, 'session-id');

        self::assertSame([], $session->all());
    }

    public function testStartDoesNotLoadDataWhenCookieIsEmpty(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->with('session-id')
            ->willReturn('');

        $request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $session = new CookieSession($request, 'session-id');

        self::assertSame([], $session->all());
    }

    public function testSetStoresValue(): void
    {
        // Note: setcookie() won't work in CLI, but we can still test the data storage
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

    public function testStartReturnsEarlyWhenIdIsEmpty(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->with('session-id')
            ->willReturn(null);

        $request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $session = new CookieSession($request, 'session-id');

        // Set id to empty to trigger the early return on line 51
        $session->setId('');
        $session->start();

        // getCookieParams was only called once (during constructor), not during the second start()
        // proving that start() returned early before reaching the cookie lookup
        self::assertSame([], $session->all());
    }

    public function testConstructorWithSessionName(): void
    {
        $request = $this->createMock(ServerRequestContract::class);

        $cookies = $this->createMock(CookieParamCollectionContract::class);
        $cookies
            ->expects($this->once())
            ->method('getParam')
            ->willReturn(null);

        $request
            ->expects($this->once())
            ->method('getCookieParams')
            ->willReturn($cookies);

        $session = new CookieSession($request, 'session-id', 'MY_SESSION');

        self::assertSame('session-id', $session->getId());
        self::assertSame('MY_SESSION', $session->getName());
    }
}
