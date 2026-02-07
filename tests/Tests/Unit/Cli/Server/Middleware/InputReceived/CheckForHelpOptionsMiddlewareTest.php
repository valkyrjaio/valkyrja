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

namespace Valkyrja\Tests\Unit\Cli\Server\Middleware\InputReceived;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Server\Constant\CommandName;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CheckForHelpOptionsMiddlewareTest extends TestCase
{
    public function testWithoutHelpOptions(): void
    {
        $input   = new Input();
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckForHelpOptionsMiddleware(
            commandName: CommandName::HELP,
            optionName: OptionName::HELP,
            optionShortName: OptionShortName::HELP,
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
    }

    public function testWithHelpOption(): void
    {
        $input   = new Input()->withOptions(new Option(name: OptionName::HELP));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForHelpOptionsMiddleware(
            commandName: CommandName::HELP,
            optionName: OptionName::HELP,
            optionShortName: OptionShortName::HELP,
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame(CommandName::HELP, $inputAfterMiddleware->getCommandName());
        self::assertNotEmpty($inputAfterMiddleware->getOptions());
        self::assertSame('command', $inputAfterMiddleware->getOptions()[0]->getName());
        self::assertSame($input->getCommandName(), $inputAfterMiddleware->getOptions()[0]->getValue());
    }

    public function testWithHelpShortOption(): void
    {
        $input   = new Input()->withOptions(new Option(name: OptionShortName::HELP));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForHelpOptionsMiddleware(
            commandName: CommandName::HELP,
            optionName: OptionName::HELP,
            optionShortName: OptionShortName::HELP,
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame(CommandName::HELP, $inputAfterMiddleware->getCommandName());
        self::assertNotEmpty($inputAfterMiddleware->getOptions());
        self::assertSame('command', $inputAfterMiddleware->getOptions()[0]->getName());
        self::assertSame($input->getCommandName(), $inputAfterMiddleware->getOptions()[0]->getValue());
    }
}
