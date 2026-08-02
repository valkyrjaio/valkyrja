<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
