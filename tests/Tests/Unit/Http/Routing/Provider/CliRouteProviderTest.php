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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliRouteProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the cli route provider service.
 */
final class CliRouteProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(HttpRoutingCliRouteProvider::getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        self::assertContains(ListCommand::class, HttpRoutingCliRouteProvider::getControllerClasses());
        self::assertContains(GenerateDataCommand::class, HttpRoutingCliRouteProvider::getControllerClasses());
    }
}
