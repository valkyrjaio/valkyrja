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

use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ListCommand service.
 */
class ListCommandTest extends TestCase
{
    public function testListCommand(): void
    {
        $path  = '/';
        $name  = 'route';
        $regex = 'regex';

        $route = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            regex: $regex,
        );

        $outputFactory = new OutputFactory();
        $version       = new VersionCommand($outputFactory);
        $collection    = new Collection();

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
        self::assertStringContainsString($path, $contents);
        self::assertStringContainsString($name, $contents);
        self::assertStringContainsString($regex, $contents);
        self::assertStringContainsString($route->getDispatch()->__toString(), $contents);
    }

    public function testNoRoutes(): void
    {
        $outputFactory = new OutputFactory();
        $version       = new VersionCommand($outputFactory);
        $collection    = new Collection();

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

    public function testHelp(): void
    {
        $text = 'A command to list all the routes present within the Http component.';

        self::assertSame($text, ListCommand::help()->getText());
        self::assertSame($text, ListCommand::help()->getFormattedText());
    }
}
