<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Server\Handler;

use Valkyrja\Application\Directory\Directory;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaRuntimeExceptionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;
use function ob_get_clean;
use function ob_start;

final class InputHandlerTest extends TestCase
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
        $exception = new ValkyrjaRuntimeExceptionFixture();

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
        self::assertSame(' list', $handledResponse->getMessages()[3]->getText());
        self::assertInstanceOf(NewLine::class, $handledResponse->getMessages()[4]);
        self::assertInstanceOf(NewLine::class, $handledResponse->getMessages()[5]);
        self::assertSame('Message:', $handledResponse->getMessages()[6]->getText());
        self::assertSame(' ' . $exception->getMessage(), $handledResponse->getMessages()[7]->getText());
    }

    public function testHandleExceptionWithThrowableCaughtMiddleware(): void
    {
        $output    = new Output();
        $input     = new Input();
        $exception = new ValkyrjaRuntimeExceptionFixture();

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

    public function testHandleReportsBothThrowablesWhenTheThrowableCaughtMiddlewareThrows(): void
    {
        $input     = new Input();
        $exception = new ValkyrjaRuntimeExceptionFixture('The command failed.');

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willThrowException($exception);

        // The middleware itself fails, so the stage cannot build the output it owns.
        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->willThrowException(new ValkyrjaRuntimeExceptionFixture('The middleware failed.'));

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        $handledOutput = $inputHandler->handle($input);

        ob_start();
        $handledOutput->writeMessages();
        $handledText = ob_get_clean();

        self::assertStringContainsString('The command failed.', (string) $handledText);
        self::assertStringContainsString('Recovery message:', (string) $handledText);
        self::assertStringContainsString('The middleware failed.', (string) $handledText);
        self::assertSame(ExitCode::ERROR, $handledOutput->getExitCode());
        self::assertSame($handledOutput, $container->get(OutputContract::class));
    }

    public function testRunReportsAProcessExitingThrowableAndKeepsTheExitCode(): void
    {
        $output = new Output(exitCode: ExitCode::USAGE_ERROR);
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        $processExitingHandler = $this->createMock(ProcessExitingHandlerContract::class);
        $processExitingHandler
            ->expects($this->once())
            ->method('processExiting')
            ->willThrowException(new ValkyrjaRuntimeExceptionFixture('The exit stage failed.'));

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            processExitingHandler: $processExitingHandler,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        // The report is the only trace the failure leaves.
        self::assertStringContainsString('Cli Server Error:', (string) $runOutput);
        self::assertStringContainsString('The exit stage failed.', (string) $runOutput);
        // The frozen exiter echoes the code, so the command's own code reaches the shell.
        self::assertStringEndsWith((string) ExitCode::USAGE_ERROR->value, (string) $runOutput);
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

    public function testRunRoutesAWriteThrowableThroughTheThrowableCaughtHandler(): void
    {
        $stream = fopen(filename: 'php://memory', mode: 'rb');

        self::assertNotFalse($stream);

        $output = new StreamOutput($stream)->withMessages(new Message('This is a test.'));
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

        self::assertStringContainsString('Cli Server Error:', (string) $runOutput);
        self::assertStringContainsString('takes no write', (string) $runOutput);
        // The recovery output replaces the command's, so the process reports 1.
        self::assertStringEndsWith('1', (string) $runOutput);
        self::assertSame(ExitCode::ERROR, $container->get(OutputContract::class)->getExitCode());
    }

    public function testRunFallsBackToStdoutWhenTheRecoveryWriteAlsoFails(): void
    {
        $readOnly = fopen(filename: 'php://memory', mode: 'rb');

        self::assertNotFalse($readOnly);

        $output = new StreamOutput($readOnly)->withMessages(new Message('This is a test.'));
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        // The middleware routes the recovery output to a second destination that also fails.
        $recoveryPath = Directory::storagePath('missing/recovery.txt');

        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->willReturn(new FileOutput($recoveryPath)->withMessages(new Message('Recovery.')));

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        $containerOutput = $container->get(OutputContract::class);

        self::assertStringContainsString('Cli Server Error:', (string) $runOutput);
        // The last resort reports the throwable the command's own destination raised.
        self::assertStringContainsString('takes no write', (string) $runOutput);
        // It also names the second destination, so the misconfiguration is visible.
        self::assertStringContainsString('Recovery message:', (string) $runOutput);
        self::assertStringContainsString($recoveryPath, (string) $runOutput);
        self::assertSame(ExitCode::ERROR, $containerOutput->getExitCode());
        self::assertNotInstanceOf(StreamOutput::class, $containerOutput);
    }

    public function testRunFallsBackToStdoutWhenTheThrowableCaughtMiddlewareThrows(): void
    {
        $readOnly = fopen(filename: 'php://memory', mode: 'rb');

        self::assertNotFalse($readOnly);

        $output = new StreamOutput($readOnly)->withMessages(new Message('This is a test.'));
        $input  = new Input();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($input)
            ->willReturn($output);

        // The middleware itself fails, so the dispatch throws before any recovery write runs.
        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->willThrowException(new ValkyrjaRuntimeExceptionFixture('The middleware failed.'));

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        $containerOutput = $container->get(OutputContract::class);

        self::assertStringContainsString('Cli Server Error:', (string) $runOutput);
        // The last resort reports the throwable the command's own destination raised.
        self::assertStringContainsString('takes no write', (string) $runOutput);
        // It also names the middleware failure, so neither throwable is lost.
        self::assertStringContainsString('Recovery message:', (string) $runOutput);
        self::assertStringContainsString('The middleware failed.', (string) $runOutput);
        self::assertSame(ExitCode::ERROR, $containerOutput->getExitCode());
    }

    public function testRunRegistersTheWrittenOutputOnTheSuccessPath(): void
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
        ob_get_clean();

        Exiter::unfreeze();

        $registered = $container->get(OutputContract::class);

        self::assertNotSame($output, $registered);
        self::assertTrue($registered->hasWrittenMessage());
        self::assertFalse($registered->hasUnwrittenMessage());
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

        // The middleware receives the written output, not the one the command returned.
        $exited      = null;
        $exitHandler = $this->createMock(ProcessExitingHandlerContract::class);
        $exitHandler
            ->expects($this->once())
            ->method('processExiting')
            ->willReturnCallback(
                static function (InputContract $exitInput, OutputContract $exitOutput) use (&$exited, $input): void {
                    self::assertSame($input, $exitInput);

                    $exited = $exitOutput;
                }
            );

        $container = new Container();

        $inputHandler = new InputHandler(
            container: $container,
            router: $router,
            processExitingHandler: $exitHandler,
        );

        Exiter::freeze();

        ob_start();
        $inputHandler->run($input);
        $runOutput = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame($output->getMessages()[0]->getFormattedText() . '0', $runOutput);
        self::assertInstanceOf(OutputContract::class, $exited);
        self::assertFalse($exited->hasUnwrittenMessage());
        self::assertTrue($exited->hasWrittenMessage());
    }
}
