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

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
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

    public function testArguments(): void
    {
        $argument  = new Argument(value: 'test');
        $argument2 = new Argument(value: 'test2');

        $arguments  = [$argument];
        $arguments2 = [$argument2];

        $input = new Input(arguments: $arguments);

        self::assertSame($arguments, $input->getArguments());

        $input2 = $input->withArguments(...$arguments2);

        self::assertNotSame($input, $input2);
        self::assertSame($arguments2, $input2->getArguments());

        $input3 = $input->withAddedArgument($argument2);

        self::assertNotSame($input, $input3);
        self::assertSame([$argument, $argument2], $input3->getArguments());

        $input4 = $input3->withoutArgument($argument2->getValue());

        self::assertNotSame($input, $input4);
        self::assertNotSame($input3, $input4);
        self::assertSame($arguments, $input4->getArguments());

        $input5 = $input->withoutArguments();

        self::assertNotSame($input, $input5);
        self::assertEmpty($input5->getArguments());
    }

    public function testOptions(): void
    {
        $option  = new Option(name: 'test');
        $option2 = new Option(name: 'test2');

        $options  = [$option];
        $options2 = [$option2];

        $input = new Input(options: $options);

        self::assertSame($options, $input->getOptions());

        $input2 = $input->withOptions(...$options2);

        self::assertNotSame($input, $input2);
        self::assertSame($options2, $input2->getOptions());

        $input3 = $input->withAddedOption($option2);

        self::assertNotSame($input, $input3);
        self::assertSame([$option, $option2], $input3->getOptions());

        $input4 = $input3->withoutOption($option2->getName());

        self::assertNotSame($input, $input4);
        self::assertNotSame($input3, $input4);
        self::assertSame($options, $input4->getOptions());

        $input5 = $input->withoutOptions();

        self::assertNotSame($input, $input5);
        self::assertEmpty($input5->getOptions());
    }
}
