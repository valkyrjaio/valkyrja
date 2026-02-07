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

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Session\Manager\CacheSession;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheSessionTest extends TestCase
{
    protected CacheContract&MockObject $cache;

    protected CacheSession $session;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheContract::class);

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('_session')
            ->willReturn(null);

        $this->session = new CacheSession($this->cache);
    }

    public function testImplementsSessionContract(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->session);
    }

    public function testStartLoadsCachedDataWhenAvailable(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('test-session_session')
            ->willReturn('{"key":"value","key2":"value2"}');

        $session = new CacheSession($cache, 'test-session');

        self::assertSame('value', $session->get('key'));
        self::assertSame('value2', $session->get('key2'));
    }

    public function testStartDoesNotLoadDataWhenCacheReturnsNull(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('test-session_session')
            ->willReturn(null);

        $session = new CacheSession($cache, 'test-session');

        self::assertSame([], $session->all());
    }

    public function testStartDoesNotLoadDataWhenCacheReturnsEmptyString(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('test-session_session')
            ->willReturn('');

        $session = new CacheSession($cache, 'test-session');

        self::assertSame([], $session->all());
    }

    public function testSetUpdatesCacheSession(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('session-id_session')
            ->willReturn(null);

        $cache
            ->expects($this->once())
            ->method('forever')
            ->with('session-id_session', '{"key":"value"}');

        $session = new CacheSession($cache, 'session-id');
        $session->set('key', 'value');

        self::assertSame('value', $session->get('key'));
    }

    public function testRemoveUpdatesCacheSessionWhenItemExists(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('session-id_session')
            ->willReturn('{"key":"value"}');

        $cache
            ->expects($this->once())
            ->method('forever')
            ->with('session-id_session', '[]');

        $session = new CacheSession($cache, 'session-id');
        $result  = $session->remove('key');

        self::assertTrue($result);
        self::assertFalse($session->has('key'));
    }

    public function testRemoveDoesNotUpdateCacheSessionWhenItemDoesNotExist(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('session-id_session')
            ->willReturn(null);

        $cache
            ->expects($this->never())
            ->method('forever');

        $session = new CacheSession($cache, 'session-id');
        $result  = $session->remove('nonexistent');

        self::assertFalse($result);
    }

    public function testClearUpdatesCacheSession(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('session-id_session')
            ->willReturn('{"key":"value"}');

        $cache
            ->expects($this->once())
            ->method('forever')
            ->with('session-id_session', '[]');

        $session = new CacheSession($cache, 'session-id');
        $session->clear();

        self::assertSame([], $session->all());
    }

    public function testDestroyUpdatesCacheSession(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->with('session-id_session')
            ->willReturn('{"key":"value"}');

        $cache
            ->expects($this->once())
            ->method('forever')
            ->with('session-id_session', '[]');

        $session = new CacheSession($cache, 'session-id');
        $session->destroy();

        self::assertSame([], $session->all());
    }

    public function testConstructorWithSessionNameSetsName(): void
    {
        $cache = $this->createMock(CacheContract::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $session = new CacheSession($cache, 'session-id', 'MY_SESSION');

        self::assertSame('MY_SESSION', $session->getName());
    }
}
