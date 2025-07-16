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

namespace Valkyrja\Tests\Unit\Application;

use Valkyrja\Application\Config;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Config service.
 *
 * @author Melech Mizrachi
 */
class ConfigTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Config();

        self::assertEmpty($data->aliases);
        self::assertEmpty($data->services);
        self::assertEmpty($data->providers);
        self::assertEmpty($data->listeners);
        self::assertEmpty($data->commands);
        self::assertEmpty($data->controllers);
    }
}
