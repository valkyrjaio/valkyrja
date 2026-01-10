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

namespace Valkyrja\Tests\Unit\Cli\Server\Handler;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception;

class InputHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $output = new Output();
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        $container = new Container();

        $requestHandler = new InputHandler(
            container: $container,
            router: $router,
        );

        $handledResponse = $requestHandler->handle($input);

        self::assertSame($output, $handledResponse);
        self::assertSame($output, $container->get(OutputContract::class));
    }

    public function testHandleWithBeforeMiddleware(): void
    {
        $output  = new Output();
        $output2 = new Output();
        $input   = new Input();

        $router = $this->createMock(Router::class);
        $router
            // Router shouldn't be called since the middleware returns an output
            ->expects($this->never())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        $beforeHandler = $this->createMock(InputReceivedHandler::class);
        $beforeHandler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($output2);

        $container = new Container();

        $requestHandler = new InputHandler(
            container: $container,
            router: $router,
            inputReceivedHandler: $beforeHandler,
        );

        $handledResponse = $requestHandler->handle($input);

        self::assertSame($output2, $handledResponse);
        self::assertSame($output2, $container->get(OutputContract::class));
    }

    public function testHandleWithBeforeMiddlewareReturningInput(): void
    {
        $output = new Output();
        $input  = new Input();
        $input2 = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input2)
            ->willReturn($output);

        $beforeHandler = $this->createMock(InputReceivedHandler::class);
        $beforeHandler
            ->expects($this->once())
            ->method('inputReceived')
            ->with($input)
            ->willReturn($input2);

        $container = new Container();

        $requestHandler = new InputHandler(
            container: $container,
            router: $router,
            inputReceivedHandler: $beforeHandler,
        );

        $handledResponse = $requestHandler->handle($input);

        self::assertSame($output, $handledResponse);
        self::assertSame($output, $container->get(OutputContract::class));
    }

    public function testHandleException(): void
    {
        $output    = new Output();
        $input     = new Input();
        $exception = new Exception();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willThrowException($exception);

        $exceptionHandler = $this->createMock(ThrowableCaughtHandler::class);
        $exceptionHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->with($input, $this->anything(), $exception)
            ->willReturnArgument(1);

        $container = new Container();

        $requestHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $exceptionHandler,
        );

        $handledResponse = $requestHandler->handle($input);

        // self::assertSame($output, $handledResponse);
        // self::assertSame($output, $container->get(OutputContract::class));
    }

    public function testHandleExceptionWithThrowableCaughtMiddleware(): void
    {
        $output    = new Output();
        $input     = new Input();
        $exception = new Exception();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willThrowException($exception);

        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->with($input, $this->anything(), $exception)
            ->willReturn($output);

        $container = new Container();

        $requestHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        $handledResponse = $requestHandler->handle($input);

        self::assertSame($output, $handledResponse);
        self::assertSame($output, $container->get(OutputContract::class));
    }
}
