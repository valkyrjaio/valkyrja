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

use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CheckGlobalInteractionOptionsMiddlewareTest extends TestCase
{
    public function testWithoutHelpOptions(): void
    {
        $config  = new Config();
        $input   = new Input();
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithNoInteractionOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionName::NO_INTERACTION));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithNoInteractionShortOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionShortName::NO_INTERACTION));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithQuietOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionName::QUIET));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithQuietShortOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionShortName::QUIET));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isSilent);
    }

    public function testWithSilentOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionName::SILENT));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithSilentShortOption(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(new Option(name: OptionShortName::SILENT));
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithAllOptions(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(
            new Option(name: OptionName::NO_INTERACTION),
            new Option(name: OptionName::QUIET),
            new Option(name: OptionName::SILENT),
        );
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertTrue($config->isSilent);
    }

    public function testWithAllShortOptions(): void
    {
        $config  = new Config();
        $input   = new Input()->withOptions(
            new Option(name: OptionShortName::NO_INTERACTION),
            new Option(name: OptionShortName::QUIET),
            new Option(name: OptionShortName::SILENT),
        );
        $handler = $this->createMock(InputReceivedHandlerContract::class);
        $handler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input);

        $middleware = new CheckGlobalInteractionOptionsMiddleware(
            config: $config,
            noInteractionOptionName: OptionName::NO_INTERACTION,
            noInteractionOptionShortName: OptionShortName::NO_INTERACTION,
            quietOptionName: OptionName::QUIET,
            quietOptionShortName: OptionShortName::QUIET,
            silentOptionName: OptionName::SILENT,
            silentOptionShortName: OptionShortName::SILENT
        );

        $inputAfterMiddleware = $middleware->inputReceived($input, $handler);

        self::assertSame($input, $inputAfterMiddleware);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isQuiet);
        self::assertTrue($config->isSilent);
    }
}
