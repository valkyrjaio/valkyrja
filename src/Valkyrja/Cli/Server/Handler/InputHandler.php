<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Data\CliInteractionConfig;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ProcessExitingHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;

class InputHandler implements InputHandlerContract
{
    public function __construct(
        protected ContainerContract $container = new Container(),
        protected RouterContract $router = new Router(),
        protected InputReceivedHandlerContract $inputReceivedHandler = new InputReceivedHandler(),
        protected ThrowableCaughtHandlerContract $throwableCaughtHandler = new ThrowableCaughtHandler(),
        protected ProcessExitingHandlerContract $processExitingHandler = new ProcessExitingHandler(),
        protected CliInteractionConfigContract $interactionConfig = new CliInteractionConfig(),
        protected OutputFactoryContract $outputFactory = new OutputFactory(),
    ) {
    }

    /**
     * Handle the input.
     *
     * @param InputContract $input The input
     */
    #[Override]
    public function handle(InputContract $input): OutputContract
    {
        try {
            $output = $this->dispatchRouter($input);
        } catch (Throwable $throwable) {
            try {
                // A middleware runs here, so the dispatch belongs under a guard of its own.
                $output = $this->getOutputFromThrowable($input, $throwable);
                $output = $this->throwableCaughtHandler->throwableCaught($input, $output, $throwable);
            } catch (Throwable $recoveryThrowable) {
                $output = $this->getRecoveryOutput($input, $throwable, $recoveryThrowable);
            }
        }

        // Set the returned output in the container
        $this->container->setSingleton(OutputContract::class, $output);

        return $output;
    }

    /**
     * Handle exiting the handler.
     *
     * @param InputContract  $input  The input
     * @param OutputContract $output The output
     */
    #[Override]
    public function exit(InputContract $input, OutputContract $output): void
    {
        // Dispatch the process exiting middleware
        $this->processExitingHandler->processExiting($input, $output);
    }

    /**
     * Run the handler.
     */
    #[Override]
    public function run(InputContract $input): void
    {
        $output = $this->handle($input);

        try {
            $output = $output->writeMessages();

            $this->container->setSingleton(OutputContract::class, $output);
        } catch (Throwable $throwable) {
            try {
                // A middleware runs here, so the dispatch belongs under the same guard as the
                // write.
                $output = $this->getOutputFromThrowable($input, $throwable);
                $output = $this->throwableCaughtHandler->throwableCaught($input, $output, $throwable);
                $output = $output->writeMessages();
            } catch (Throwable $recoveryThrowable) {
                // The build of the first report, the dispatch, or the write of the output the
                // stage returned failed. That output can hold the destination that just failed.
                $output = $this->getRecoveryOutput($input, $throwable, $recoveryThrowable);

                $output = $output->writeMessages();
            }

            $this->container->setSingleton(OutputContract::class, $output);
        }

        try {
            $this->exit($input, $output);
        } catch (Throwable $exitThrowable) {
            try {
                // The exit stage above runs a middleware, and the run still ends through
                // Exiter::exit, so this report is the only trace the failure leaves. A silent
                // run suppresses it, and a raise inside it takes the recovery report.
                $this->getOutputFromThrowable($input, $exitThrowable)->writeMessages();
            } catch (Throwable $reportThrowable) {
                $this->getRecoveryOutput($input, $exitThrowable, $reportThrowable)->writeMessages();
            }
        }

        Exiter::exit($this->getExitCode($input, $output));
    }

    /**
     * Dispatch the input via the router.
     *
     * @param InputContract $input The input
     */
    protected function dispatchRouter(InputContract $input): OutputContract
    {
        // Set the request object in the container
        $this->container->setSingleton(InputContract::class, $input);

        // Dispatch the before input received middleware
        $inputAfterMiddleware = $this->inputReceivedHandler->inputReceived($input);

        // If the return value after middleware is a response return it
        if ($inputAfterMiddleware instanceof OutputContract) {
            return $inputAfterMiddleware;
        }

        // Set the returned request in the container
        $this->container->setSingleton(InputContract::class, $inputAfterMiddleware);

        return $this->router->dispatch($inputAfterMiddleware);
    }

    /**
     * Get an output from a throwable.
     *
     * @param InputContract $input     The input
     * @param Throwable     $throwable The throwable
     */
    protected function getOutputFromThrowable(InputContract $input, Throwable $throwable): OutputContract
    {
        return $this->outputFactory
            ->createOutput(exitCode: ExitCode::ERROR)
            ->withMessages(...$this->getThrowableMessages($input, $throwable));
    }

    /**
     * Get the code the output ends the process with.
     *
     * An output supplies this value, and a contract implementation can raise on the read. The
     * code must reach the shell either way.
     *
     * @param InputContract  $input  The input
     * @param OutputContract $output The output the run ends with
     */
    private function getExitCode(InputContract $input, OutputContract $output): int
    {
        try {
            $exitCode = $output->getExitCode();
        } catch (Throwable $codeThrowable) {
            // This read runs last, so the report is the only trace the failure leaves.
            $this->getRecoveryOutput($input, $codeThrowable)->writeMessages();

            return ExitCode::ERROR->value;
        }

        return $exitCode instanceof ExitCode
            ? $exitCode->value
            : $exitCode;
    }

    /**
     * Get the output that reports a throwable and the throwable a recovery raised.
     *
     * A first report goes through the `OutputFactory`, so a `--silent` run suppresses it. This
     * recovery report takes an `Output` this handler builds, which no configured factory can
     * redirect and no flag suppresses. Every unguarded write of this output rests on that, so
     * this method and the messages it builds take no override.
     *
     * @param InputContract  $input             The input
     * @param Throwable      $throwable         The throwable
     * @param Throwable|null $recoveryThrowable [optional] The throwable a recovery raised
     */
    private function getRecoveryOutput(
        InputContract $input,
        Throwable $throwable,
        Throwable|null $recoveryThrowable = null
    ): OutputContract {
        $recoveryMessages = $recoveryThrowable !== null
            ? $this->getRecoveryMessages($recoveryThrowable)
            : [];

        try {
            $messages = [
                ...$this->getThrowableMessages($input, $throwable),
                ...$recoveryMessages,
            ];
        } catch (Throwable) {
            // The first report reads the command name from the input, so an input that
            // raises there takes the report with it.
            $messages = [
                ...$this->getBareThrowableMessages($throwable),
                ...$recoveryMessages,
            ];
        }

        return new Output(exitCode: ExitCode::ERROR)->withMessages(...$messages);
    }

    /**
     * Get the messages that report the throwable a recovery raised.
     *
     * @param Throwable $recoveryThrowable The throwable the recovery raised
     *
     * @return MessageContract[]
     */
    private function getRecoveryMessages(Throwable $recoveryThrowable): array
    {
        return [
            new NewLine(),
            new ErrorMessage('Recovery message:'),
            new Message(' ' . $recoveryThrowable->getMessage()),
            new NewLine(),
        ];
    }

    /**
     * Get the messages that report one throwable without reading the input.
     *
     * @param Throwable $throwable The throwable
     *
     * @return MessageContract[]
     */
    private function getBareThrowableMessages(Throwable $throwable): array
    {
        return [
            new Banner(new ErrorMessage('Cli Server Error:')),
            new NewLine(),
            new ErrorMessage('Message:'),
            new Message(' ' . $throwable->getMessage()),
            new NewLine(),
        ];
    }

    /**
     * Get the messages that report a throwable.
     *
     * @param InputContract $input     The input
     * @param Throwable     $throwable The throwable
     *
     * @return MessageContract[]
     */
    private function getThrowableMessages(InputContract $input, Throwable $throwable): array
    {
        $commandName = $input->getCommandName();

        return [
            new Banner(new ErrorMessage('Cli Server Error:')),
            new NewLine(),
            new ErrorMessage('Command:'),
            new Message(" $commandName"),
            new NewLine(),
            new NewLine(),
            new ErrorMessage('Message:'),
            new Message(' ' . $throwable->getMessage()),
            // The report ends the line it wrote, so the shell prompt does not land on it.
            new NewLine(),
        ];
    }
}
