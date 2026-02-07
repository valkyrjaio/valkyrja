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

namespace Valkyrja\Tests\Unit\Cache\Manager;

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\LogCache;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class LogCacheTest extends TestCase
{
    protected MockObject&LoggerContract $logger;

    protected LogCache $cache;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerContract::class);
        $this->cache  = new LogCache($this->logger);
    }

    public function testInstanceOfContract(): void
    {
        $this->logger->expects($this->never())->method('info');

        self::assertInstanceOf(CacheContract::class, $this->cache);
    }

    public function testHasLogsAndReturnsTrue(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertTrue($this->cache->has('test-key'));
    }

    public function testGetLogsAndReturnsEmptyString(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertSame('', $this->cache->get('test-key'));
    }

    /**
     * @throws JsonException
     */
    public function testManyLogsAndReturnsEmptyArray(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertSame([], $this->cache->many('key1', 'key2'));
    }

    public function testPutLogs(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->cache->put('key', 'value', 10);
    }

    /**
     * @throws JsonException
     */
    public function testPutManyLogs(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->cache->putMany(['key1' => 'value1'], 10);
    }

    public function testIncrementLogsAndReturnsValue(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertSame(5, $this->cache->increment('key', 5));
    }

    public function testDecrementLogsAndReturnsValue(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertSame(3, $this->cache->decrement('key', 3));
    }

    public function testForeverLogs(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->cache->forever('key', 'value');
    }

    public function testForgetLogsAndReturnsTrue(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertTrue($this->cache->forget('key'));
    }

    public function testFlushLogsAndReturnsTrue(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info');

        self::assertTrue($this->cache->flush());
    }

    public function testGetPrefixReturnsEmptyStringByDefault(): void
    {
        $this->logger->expects($this->never())->method('info');

        self::assertSame('', $this->cache->getPrefix());
    }

    public function testGetPrefixReturnsConfiguredPrefix(): void
    {
        $this->logger->expects($this->never())->method('info');

        $cache = new LogCache($this->logger, 'my-prefix:');

        self::assertSame('my-prefix:', $cache->getPrefix());
    }

    public function testGetTaggerReturnsTaggerContract(): void
    {
        $this->logger->expects($this->never())->method('info');

        $tagger = $this->cache->getTagger('tag1', 'tag2');

        self::assertInstanceOf(TaggerContract::class, $tagger);
    }
}
