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

use Valkyrja\Cache\Data\CacheLogConfig;
use Valkyrja\Cache\Data\Contract\CacheLogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheLogConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CacheLogConfigContract::class, new CacheLogConfig());
    }

    public function testDefaults(): void
    {
        $config = new CacheLogConfig();

        self::assertSame(LoggerContract::class, $config->logLogger);
        self::assertSame('', $config->logPrefix);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new CacheLogConfig(
            logLogger: PsrLogger::class,
            logPrefix: 'test:',
        );

        self::assertSame(PsrLogger::class, $config->logLogger);
        self::assertSame('test:', $config->logPrefix);
    }
}
