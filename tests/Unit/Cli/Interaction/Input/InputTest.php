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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Input;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Input class.
 *
 * @author Melech Mizrachi
 */
class InputTest extends TestCase
{
    public function testDefaults(): void
    {
        $input = new Input();

        self::assertSame('valkyrja', $input->getCaller());
        self::assertSame('list', $input->getCommandName());
        self::assertEmpty($input->getArguments());
        self::assertEmpty($input->getOptions());
    }

    public function testCaller(): void
    {
        $caller  = 'caller';
        $caller2 = 'caller2';

        $input = new Input(caller: $caller);

        self::assertSame($caller, $input->getCaller());

        $input2 = $input->withCaller($caller2);

        self::assertNotSame($input, $input2);
        self::assertSame($caller2, $input2->getCaller());
    }

    public function testCommandName(): void
    {
        $command  = 'commandName';
        $command2 = 'commandName2';

        $input = new Input(commandName: $command);

        self::assertSame($command, $input->getCommandName());

        $input2 = $input->withCommandName($command2);

        self::assertNotSame($input, $input2);
        self::assertSame($command2, $input2->getCommandName());
    }
}
