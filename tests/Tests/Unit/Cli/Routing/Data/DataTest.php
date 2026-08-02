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

use stdClass;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function serialize;

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
            'command1' => serialize(new stdClass()),
            'command2' => serialize(new stdClass()),
        ];

        $data = new CliRoutingData($commands);

        self::assertSame($commands, $data->routes);
    }
}
