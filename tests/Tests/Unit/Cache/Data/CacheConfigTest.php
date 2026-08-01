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

namespace Valkyrja\Tests\Unit\Cache\Data;

use Valkyrja\Cache\Data\CacheConfig;
use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CacheConfigContract::class, new CacheConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(RedisCache::class, new CacheConfig()->defaultCache);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(NullCache::class, new CacheConfig(defaultCache: NullCache::class)->defaultCache);
    }
}
