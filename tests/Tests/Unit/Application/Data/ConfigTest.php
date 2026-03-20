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
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Data\Config;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Config service.
 */
final class ConfigTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Config();

        self::assertSame('production', $data->environment);
        self::assertSame(ApplicationInfo::VERSION, $data->version);
        self::assertFalse($data->debugMode);
        self::assertNotEmpty($data->providers);
        self::assertSame(
            [
                ComponentClass::CONTAINER,
                ComponentClass::DISPATCHER,
                ComponentClass::CLI_INTERACTION,
                ComponentClass::CLI_MIDDLEWARE,
                ComponentClass::CLI_ROUTING,
                ComponentClass::CLI_SERVER,
                ComponentClass::EVENT,
                ComponentClass::HTTP_MESSAGE,
                ComponentClass::HTTP_MIDDLEWARE,
                ComponentClass::HTTP_ROUTING,
                ComponentClass::HTTP_ROUTING_CLI,
                ComponentClass::HTTP_SERVER,
                ComponentClass::LOG,
                ComponentClass::VIEW,
            ],
            $data->providers
        );
        self::assertSame('UTC', $data->timezone);
    }
}
