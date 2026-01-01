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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Factory\InputFactory;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ArgumentFactory class.
 */
class InputFactoryTest extends TestCase
{
    public function testDefaults(): void
    {
        $_SERVER['argv'] = [];

        $input = InputFactory::fromGlobals();

        self::assertSame('valkyrja', $input->getCaller());
        self::assertSame('list', $input->getCommandName());
        self::assertEmpty($input->getArguments());
        self::assertEmpty($input->getOptions());
    }

    public function testFromGlobals(): void
    {
        $_SERVER['argv'] = [
            'cli',
            'command',
            '-t',
            '-v=value',
            '--value',
            '--value2=test',
            'argument',
            'argument2',
        ];

        $input = InputFactory::fromGlobals();

        self::assertSame('cli', $input->getCaller());
        self::assertSame('command', $input->getCommandName());
        self::assertCount(2, $input->getArguments());
        self::assertCount(4, $input->getOptions());
    }

    public function testFromGlobalsWithPassedArgs(): void
    {
        $args = [
            'cli',
            'command',
            '-t',
            '-v=value',
            '--value',
            '--value2=test',
            'argument',
            'argument2',
        ];

        $input = InputFactory::fromGlobals(args: $args);

        self::assertSame('cli', $input->getCaller());
        self::assertSame('command', $input->getCommandName());
        self::assertCount(2, $input->getArguments());
        self::assertCount(4, $input->getOptions());
    }
}
