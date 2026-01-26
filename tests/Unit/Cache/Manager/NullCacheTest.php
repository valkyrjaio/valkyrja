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

use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Tagger\Contract\TaggerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NullCacheTest extends TestCase
{
    protected NullCache $cache;

    protected function setUp(): void
    {
        $this->cache = new NullCache();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(CacheContract::class, $this->cache);
    }

    public function testHasAlwaysReturnsTrue(): void
    {
        self::assertTrue($this->cache->has('any-key'));
        self::assertTrue($this->cache->has(''));
    }

    public function testGetReturnsEmptyString(): void
    {
        self::assertSame('', $this->cache->get('any-key'));
    }

    public function testManyReturnsEmptyArray(): void
    {
        self::assertSame([], $this->cache->many('key1', 'key2'));
    }

    public function testPutDoesNothing(): void
    {
        // Should not throw any exceptions
        $this->cache->put('key', 'value', 10);

        self::assertTrue(true);
    }

    public function testPutManyDoesNothing(): void
    {
        // Should not throw any exceptions
        $this->cache->putMany(['key1' => 'value1', 'key2' => 'value2'], 10);

        self::assertTrue(true);
    }

    public function testIncrementReturnsValue(): void
    {
        self::assertSame(1, $this->cache->increment('key'));
        self::assertSame(5, $this->cache->increment('key', 5));
    }

    public function testDecrementReturnsValue(): void
    {
        self::assertSame(1, $this->cache->decrement('key'));
        self::assertSame(5, $this->cache->decrement('key', 5));
    }

    public function testForeverDoesNothing(): void
    {
        // Should not throw any exceptions
        $this->cache->forever('key', 'value');

        self::assertTrue(true);
    }

    public function testForgetReturnsTrue(): void
    {
        self::assertTrue($this->cache->forget('any-key'));
    }

    public function testFlushReturnsTrue(): void
    {
        self::assertTrue($this->cache->flush());
    }

    public function testGetPrefixReturnsEmptyStringByDefault(): void
    {
        self::assertSame('', $this->cache->getPrefix());
    }

    public function testGetPrefixReturnsConfiguredPrefix(): void
    {
        $cache = new NullCache('my-prefix:');

        self::assertSame('my-prefix:', $cache->getPrefix());
    }

    public function testGetTaggerReturnsTaggerContract(): void
    {
        $tagger = $this->cache->getTagger('tag1', 'tag2');

        self::assertInstanceOf(TaggerContract::class, $tagger);
    }
}
