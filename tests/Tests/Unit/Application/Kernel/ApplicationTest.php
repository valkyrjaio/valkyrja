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

namespace Valkyrja\Tests\Unit\Application\Kernel;

use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Application service.
 */
class ApplicationTest extends TestCase
{
    /**
     * Test the application with defaults.
     */
    public function testDefaults(): void
    {
        $config    = new Config();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            config: $config,
        );

        self::assertSame($container, $application->getContainer());
        self::assertSame($config->providers, $application->getProviders());
        self::assertSame($config->environment, $application->getEnvironment());
        self::assertSame($config->debugMode, $application->getDebugMode());
        self::assertSame($config->version, $application->getVersion());
        self::assertSame($config->timezone, date_default_timezone_get());
    }
}
