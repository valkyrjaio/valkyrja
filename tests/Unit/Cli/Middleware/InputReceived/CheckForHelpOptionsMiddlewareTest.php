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

namespace Valkyrja\Tests\Unit\Cli\Middleware\InputReceived;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Routing\Data\Option\HelpOptionParameter;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

class CheckForHelpOptionsMiddlewareTest extends TestCase
{
    public function testWithoutHelpOptions(): void
    {
        $env     = new EnvClass();
        $input   = new Input();
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckForHelpOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
    }

    public function testWithHelpOption(): void
    {
        $env     = new EnvClass();
        $input   = new Input()->withOptions(new Option(name: HelpOptionParameter::NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForHelpOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame($env::CLI_HELP_COMMAND_NAME, $inputAfterMiddleware->getCommandName());
        self::assertNotEmpty($inputAfterMiddleware->getOptions());
        self::assertSame('command', $inputAfterMiddleware->getOptions()[0]->getName());
        self::assertSame($input->getCommandName(), $inputAfterMiddleware->getOptions()[0]->getValue());
    }

    public function testWithHelpShortOption(): void
    {
        $env     = new EnvClass();
        $input   = new Input()->withOptions(new Option(name: HelpOptionParameter::SHORT_NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForHelpOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame($env::CLI_HELP_COMMAND_NAME, $inputAfterMiddleware->getCommandName());
        self::assertNotEmpty($inputAfterMiddleware->getOptions());
        self::assertSame('command', $inputAfterMiddleware->getOptions()[0]->getName());
        self::assertSame($input->getCommandName(), $inputAfterMiddleware->getOptions()[0]->getValue());
    }
}
