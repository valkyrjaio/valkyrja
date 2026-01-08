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

use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Routing\Data\Option\NoInteractionOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\QuietOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\SilentOptionParameter;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

class CheckGlobalInteractionOptionsMiddlewareTest extends TestCase
{
    public function testWithoutHelpOptions(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input();
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithNoInteractionOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: NoInteractionOptionParameter::NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithNoInteractionShortOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: NoInteractionOptionParameter::SHORT_NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithQuietOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: QuietOptionParameter::NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithQuietShortOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: QuietOptionParameter::SHORT_NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithSilentOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: SilentOptionParameter::NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithSilentShortOption(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: SilentOptionParameter::SHORT_NAME));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithAllOptions(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(
            new Option(name: NoInteractionOptionParameter::NAME),
            new Option(name: QuietOptionParameter::NAME),
            new Option(name: SilentOptionParameter::NAME),
        );
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithAllShortOptions(): void
    {
        $env     = new EnvClass();
        $config  = new Config();
        $input   = new Input()->withOptions(
            new Option(name: NoInteractionOptionParameter::SHORT_NAME),
            new Option(name: QuietOptionParameter::SHORT_NAME),
            new Option(name: SilentOptionParameter::SHORT_NAME),
        );
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(config: $config, env: $env);

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertTrue($config->isSilent);
    }
}
