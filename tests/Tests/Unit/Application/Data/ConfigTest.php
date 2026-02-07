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

namespace Valkyrja\Tests\Unit\Application\Data;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\Config;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Config service.
 */
class ConfigTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Config();

        self::assertSame('production', $data->environment);
        self::assertSame(ApplicationInfo::VERSION, $data->version);
        self::assertFalse($data->debugMode);
        self::assertEmpty($data->providers);
        self::assertSame('UTC', $data->timezone);
    }
}
