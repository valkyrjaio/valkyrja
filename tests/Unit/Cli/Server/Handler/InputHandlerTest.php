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
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception;

use function ob_start;

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

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
        );

        $handledResponse = $inputHandler->handle($input);

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

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            inputReceivedHandler: $beforeHandler,
        );

        $handledResponse = $inputHandler->handle($input);

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

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            inputReceivedHandler: $beforeHandler,
        );

        $handledResponse = $inputHandler->handle($input);

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
            ->with($input, self::anything(), $exception)
            ->willReturnArgument(1);

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $exceptionHandler,
        );

        $handledResponse = $inputHandler->handle($input);

        self::assertNotSame($output, $handledResponse);
        self::assertNotEmpty($handledResponse->getMessages());
        self::assertNotEmpty($handledResponse->getUnwrittenMessages());
        self::assertSame($handledResponse->getMessages(), $handledResponse->getUnwrittenMessages());
        self::assertInstanceOf(Banner::class, $handledResponse->getMessages()[0]);
        self::assertStringContainsString('Cli Server Error:', $handledResponse->getMessages()[0]->getText());
        self::assertInstanceOf(NewLine::class, $handledResponse->getMessages()[1]);
        self::assertSame('Command:', $handledResponse->getMessages()[2]->getText());
        self::assertSame(" list", $handledResponse->getMessages()[3]->getText());
        self::assertInstanceOf(NewLine::class, $handledResponse->getMessages()[4]);
        self::assertInstanceOf(NewLine::class, $handledResponse->getMessages()[5]);
        self::assertSame('Message:', $handledResponse->getMessages()[6]->getText());
        self::assertSame(' ' . $exception->getMessage(), $handledResponse->getMessages()[7]->getText());
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
            ->with($input, self::anything(), $exception)
            ->willReturn($output);

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        $handledResponse = $inputHandler->handle($input);

        self::assertSame($output, $handledResponse);
        self::assertSame($output, $container->get(OutputContract::class));
    }

    public function testRun(): void
    {
        $output = new Output()->withMessages(new Message('This is a test.'));
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame($output->getMessages()[0]->getFormattedText() . '0', $runOutput);
    }

    public function testHandleExitHandler(): void
    {
        $output = new Output()->withMessages(new Message('This is a test.'));
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        $exitHandler = $this->createMock(ExitedHandlerContract::class);
        $exitHandler
            ->expects($this->once())
            ->method('exited')
            ->with($input, $output);

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            exitedHandler: $exitHandler,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame($output->getMessages()[0]->getFormattedText() . '0', $runOutput);
    }
}
