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

use PHPUnit\Framework\MockObject\MockObject;
use Predis\Client;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Tests\Classes\Vendor\Predis\ClientClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function is_callable;

class RedisCacheTest extends TestCase
{
    protected MockObject&Client $client;

    protected RedisCache $cache;

    protected string $prefix = 'test:';

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientClass::class);
        $this->cache  = new RedisCache($this->client, $this->prefix);
    }

    public function testInstanceOfContract(): void
    {
        $this->client->expects($this->never())->method('exists');
        $this->client->expects($this->never())->method('get');

        self::assertInstanceOf(CacheContract::class, $this->cache);
    }

    public function testHasReturnsTrueWhenKeyExists(): void
    {
        $this->client
            ->expects($this->once())
            ->method('exists')
            ->with($this->prefix . 'my-key')
            ->willReturn(1);

        self::assertTrue($this->cache->has('my-key'));
    }

    public function testHasReturnsFalseWhenKeyDoesNotExist(): void
    {
        $this->client
            ->expects($this->once())
            ->method('exists')
            ->with($this->prefix . 'my-key')
            ->willReturn(0);

        self::assertFalse($this->cache->has('my-key'));
    }

    public function testGetReturnsValueFromRedis(): void
    {
        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($this->prefix . 'my-key')
            ->willReturn('cached-value');

        self::assertSame('cached-value', $this->cache->get('my-key'));
    }

    public function testGetReturnsNullWhenKeyNotFound(): void
    {
        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($this->prefix . 'my-key')
            ->willReturn(null);

        self::assertNull($this->cache->get('my-key'));
    }

    public function testManyReturnsValuesFromRedis(): void
    {
        $this->client
            ->expects($this->once())
            ->method('mget')
            ->with([$this->prefix . 'key1', $this->prefix . 'key2'])
            ->willReturn(['value1', 'value2']);

        self::assertSame(['value1', 'value2'], $this->cache->many('key1', 'key2'));
    }

    public function testPutStoresValueWithExpiry(): void
    {
        $this->client
            ->expects($this->once())
            ->method('setex')
            ->with($this->prefix . 'my-key', 10, 'my-value');

        $this->cache->put('my-key', 'my-value', 10);
    }

    public function testPutManyStoresMultipleValuesInTransaction(): void
    {
        $client     = $this->client;
        $values     = ['key1' => 'value1', 'key2' => 'value2'];
        $seconds    = 10;
        $countKey   = 0;
        $countValue = 0;

        $client
            ->expects($this->once())
            ->method('transaction')
            ->with(
                self::callback(
                    static function (mixed $callback) use ($client): bool {
                        $callback($client);

                        return is_callable($callback);
                    }
                )
            );

        $client
            ->expects($this->exactly(2))
            ->method('setex')
            ->with(
                self::callback(
                    function (string $key) use (&$countKey): bool {
                        if ($countKey === 0) {
                            $countKey++;

                            return $key === $this->prefix . 'key1';
                        }

                        return $key === $this->prefix . 'key2';
                    }
                ),
                $seconds,
                self::callback(
                    static function (string $value) use (&$countValue): bool {
                        if ($countValue === 0) {
                            $countValue++;

                            return $value === 'value1';
                        }

                        return $value === 'value2';
                    }
                ),
            );

        $this->cache->putMany($values, $seconds);
    }

    public function testIncrementIncrementsValue(): void
    {
        $this->client
            ->expects($this->once())
            ->method('incrby')
            ->with($this->prefix . 'my-key', 5)
            ->willReturn(6);

        self::assertSame(6, $this->cache->increment('my-key', 5));
    }

    public function testDecrementDecrementsValue(): void
    {
        $this->client
            ->expects($this->once())
            ->method('decrby')
            ->with($this->prefix . 'my-key', 3)
            ->willReturn(2);

        self::assertSame(2, $this->cache->decrement('my-key', 3));
    }

    public function testForeverStoresValueWithoutExpiry(): void
    {
        $this->client
            ->expects($this->once())
            ->method('set')
            ->with($this->prefix . 'my-key', 'my-value');

        $this->cache->forever('my-key', 'my-value');
    }

    public function testForgetDeletesKey(): void
    {
        $this->client
            ->expects($this->once())
            ->method('del')
            ->with([$this->prefix . 'my-key'])
            ->willReturn(1);

        self::assertTrue($this->cache->forget('my-key'));
    }

    public function testForgetReturnsFalseWhenKeyNotDeleted(): void
    {
        $this->client
            ->expects($this->once())
            ->method('del')
            ->with([$this->prefix . 'my-key'])
            ->willReturn(0);

        self::assertFalse($this->cache->forget('my-key'));
    }

    public function testFlushClearsDatabase(): void
    {
        $this->client
            ->expects($this->once())
            ->method('flushdb')
            ->willReturn(true);

        self::assertTrue($this->cache->flush());
    }

    public function testGetPrefixReturnsPrefix(): void
    {
        $this->client->expects($this->never())->method('get');

        self::assertSame($this->prefix, $this->cache->getPrefix());
    }

    public function testGetTaggerReturnsTaggerContract(): void
    {
        $this->client->expects($this->never())->method('get');

        $tagger = $this->cache->getTagger('tag1', 'tag2');

        self::assertInstanceOf(TaggerContract::class, $tagger);
    }
}
