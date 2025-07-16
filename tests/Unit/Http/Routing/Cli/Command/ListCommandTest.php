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

namespace Valkyrja\Tests\Unit\Http\Routing\Cli\Command;

use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Command\VersionCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ListCommand service.
 *
 * @author Melech Mizrachi
 */
class ListCommandTest extends TestCase
{
    public function testListCommand(): void
    {
        $route = new Route(
            path: '/',
            name: 'route',
        );

        $version       = new VersionCommand();
        $collection    = new Collection();
        $outputFactory = new OutputFactory();

        $listCommand = new ListCommand();

        $collection->add($route);

        ob_start();
        $output = $listCommand->run(
            version: $version,
            collection: $collection,
            outputFactory: $outputFactory
        );
        $output->writeMessages();
        $contents = ob_get_clean();

        self::assertIsString($contents);
        self::assertStringContainsString($route->getPath(), $contents);
        self::assertStringContainsString($route->getName(), $contents);
        self::assertStringContainsString($route->getDispatch()->__toString(), $contents);
    }

    public function testNoRoutes(): void
    {
        $version       = new VersionCommand();
        $collection    = new Collection();
        $outputFactory = new OutputFactory();

        $listCommand = new ListCommand();

        $output = $listCommand->run(
            $version,
            $collection,
            $outputFactory
        );

        ob_start();
        $output->writeMessages();
        $contents = ob_get_clean();

        self::assertIsString($contents);
        self::assertStringContainsString('No routes were found', $contents);
    }
}
