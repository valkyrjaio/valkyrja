<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Cli\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Data service.
 */
final class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new CliRoutingData();

        self::assertEmpty($data->routes);
    }

    public function testWithCommands(): void
    {
        $commands = [
            'command1' => static fn (): RouteContract => new Route('command1', 'description', handler: RouteHandlerFixture::handle(...)),
            'command2' => static fn (): RouteContract => new Route('command2', 'description', handler: RouteHandlerFixture::handle(...)),
        ];

        $data = new CliRoutingData($commands);

        self::assertSame($commands, $data->routes);
    }
}
