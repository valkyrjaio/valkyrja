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

namespace Valkyrja\Tests\Unit\Cli\Routing\Attribute;

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the CLI route attribute.
 */
final class RouteTest extends TestCase
{
    public function testDefaults(): void
    {
        $route = new Route(name: 'test', description: 'A test command');

        self::assertSame('test', $route->getName());
        self::assertSame('A test command', $route->getDescription());
    }

    public function testDefaultHandlerReturnsOutput(): void
    {
        $route = new Route(name: 'test', description: 'A test command');

        $handler  = $route->getHandler();
        $response = $handler(self::createStub(ContainerContract::class), $route);

        self::assertInstanceOf(OutputContract::class, $response);
    }
}
