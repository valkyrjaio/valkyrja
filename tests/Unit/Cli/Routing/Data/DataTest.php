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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use stdClass;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Tests\Unit\TestCase;

use function serialize;

/**
 * Test the Data service.
 */
class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new Data();

        self::assertEmpty($data->commands);
    }

    public function testWithCommands(): void
    {
        $commands = [
            'command1' => serialize(new stdClass()),
            'command2' => serialize(new stdClass()),
        ];

        $data = new Data($commands);

        self::assertSame($commands, $data->commands);
    }
}
