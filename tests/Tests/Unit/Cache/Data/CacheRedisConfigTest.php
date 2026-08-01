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
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheRedisConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        $config = new CacheRedisConfig();

        self::assertSame('127.0.0.1', $config->host);
        self::assertSame(6379, $config->port);
        self::assertSame('', $config->prefix);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new CacheRedisConfig(
            host: 'redis.test',
            port: 6380,
            prefix: 'test:',
        );

        self::assertSame('redis.test', $config->host);
        self::assertSame(6380, $config->port);
        self::assertSame('test:', $config->prefix);
    }
}
