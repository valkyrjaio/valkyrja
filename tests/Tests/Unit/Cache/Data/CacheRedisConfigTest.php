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

use Valkyrja\Cache\Data\CacheRedisConfig;
use Valkyrja\Cache\Data\Contract\CacheRedisConfigContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheRedisConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CacheRedisConfigContract::class, new CacheRedisConfig());
    }

    public function testDefaults(): void
    {
        $config = new CacheRedisConfig();

        self::assertSame('127.0.0.1', $config->redisHost);
        self::assertSame(6379, $config->redisPort);
        self::assertSame('', $config->redisPrefix);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new CacheRedisConfig(
            redisHost: 'redis.test',
            redisPort: 6380,
            redisPrefix: 'test:',
        );

        self::assertSame('redis.test', $config->redisHost);
        self::assertSame(6380, $config->redisPort);
        self::assertSame('test:', $config->redisPrefix);
    }
}
