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
use Valkyrja\Cli\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Routing\Data\Option\VersionOptionParameter;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

class CheckForVersionOptionsMiddlewareTest extends TestCase
{
    public function testWithoutVersionOptions(): void
    {
        $env     = new EnvClass();
        $input   = new Input();
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckForVersionOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
    }

    public function testWithVersionOption(): void
    {
        $env     = new EnvClass();
        $input   = new Input()->withOptions(new Option(name: VersionOptionParameter::NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForVersionOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame($env::CLI_VERSION_COMMAND_NAME, $inputAfterMiddleware->getCommandName());
        self::assertEmpty($inputAfterMiddleware->getOptions());
    }

    public function testWithVersionShortOption(): void
    {
        $env     = new EnvClass();
        $input   = new Input()->withOptions(new Option(name: VersionOptionParameter::SHORT_NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->willReturnArgument(0);

        $middleware = new CheckForVersionOptionsMiddleware(env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertNotSame($input, $inputAfterMiddleware);
        self::assertSame($env::CLI_VERSION_COMMAND_NAME, $inputAfterMiddleware->getCommandName());
        self::assertEmpty($inputAfterMiddleware->getOptions());
    }
}
