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

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use Valkyrja\Cli\Routing\Provider\CliRouteProvider;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the cli route provider service.
 */
final class CliRouteProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(CliRouteProvider::getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        $controllers = CliRouteProvider::getControllerClasses();

        self::assertContains(HelpCommand::class, $controllers);
        self::assertContains(ListBashCommand::class, $controllers);
        self::assertContains(ListCommand::class, $controllers);
        self::assertContains(VersionCommand::class, $controllers);
    }
}
