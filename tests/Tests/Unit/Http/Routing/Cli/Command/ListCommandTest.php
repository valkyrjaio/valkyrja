<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Cli\Command;

use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Tests\Fixtures\Http\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_start;
use function strpos;

/**
 * Test the ListCommand service.
 */
final class ListCommandTest extends TestCase
{
    public function testListCommand(): void
    {
        $path  = '/';
        $name  = 'route';
        $regex = 'regex';

        $route = new DynamicRoute(
            path: $path,
            name: $name,
            regex: $regex,
            parameters: [],
            handler: RouteHandlerFixture::handle(...),
        );

        $outputFactory = new OutputFactory();
        $version       = new VersionCommand($outputFactory, new CliConfig(), self::createStub(RouteContract::class));
        $collection    = new RouteCollection();

        $listCommand = new ListCommand();

        $collection->add($route);

        ob_start();
        $output = $listCommand->run(
            version: $version,
            collection: $collection,
            outputFactory: $outputFactory
        );
        $output->writeMessages();
        $contents = self::cleanOutputBuffer();

        self::assertIsString($contents);
        self::assertStringContainsString($path, $contents);
        self::assertStringContainsString($name, $contents);
        self::assertStringContainsString($regex, $contents);
    }

    public function testSortsMultipleRoutesByPath(): void
    {
        $outputFactory = new OutputFactory();
        $version       = new VersionCommand($outputFactory, new CliConfig(), self::createStub(RouteContract::class));
        $collection    = new RouteCollection();

        $collection->add(new DynamicRoute(
            path: '/zebra',
            name: 'zebra',
            regex: 'z',
            parameters: [],
            handler: RouteHandlerFixture::handle(...),
        ));
        $collection->add(new DynamicRoute(
            path: '/apple',
            name: 'apple',
            regex: 'a',
            parameters: [],
            handler: RouteHandlerFixture::handle(...),
        ));

        $listCommand = new ListCommand();

        ob_start();
        $output = $listCommand->run(
            version: $version,
            collection: $collection,
            outputFactory: $outputFactory
        );
        $output->writeMessages();
        $contents = self::cleanOutputBuffer();

        $applePos = strpos((string) $contents, '/apple');
        $zebraPos = strpos((string) $contents, '/zebra');

        self::assertIsInt($applePos);
        self::assertIsInt($zebraPos);
        // The usort comparator runs with 2 routes; /apple sorts before /zebra.
        self::assertLessThan($zebraPos, $applePos);
    }

    public function testNoRoutes(): void
    {
        $outputFactory = new OutputFactory();
        $version       = new VersionCommand($outputFactory, new CliConfig(), self::createStub(RouteContract::class));
        $collection    = new RouteCollection();

        $listCommand = new ListCommand();

        $output = $listCommand->run(
            $version,
            $collection,
            $outputFactory
        );

        ob_start();
        $output->writeMessages();
        $contents = self::cleanOutputBuffer();

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
