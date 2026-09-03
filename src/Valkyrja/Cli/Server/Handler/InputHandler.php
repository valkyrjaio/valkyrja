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
                $output = new Output(exitCode: ExitCode::ERROR)
                    ->withMessages(...$this->getFallbackThrowableMessages($throwable, $recoveryThrowable));
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
                // The dispatch or the recovery write failed. A middleware can throw, or it can
                // return an output whose destination is the one that just failed. This last resort
                // echoes, so no configured factory can redirect it. It leads with the throwable the
                // command's own destination raised, and it names both failures.
                $messages = $this->getFallbackThrowableMessages($throwable, $recoveryThrowable);

                $output = new Output(exitCode: ExitCode::ERROR)->withMessages(...$messages);

                $output = $output->writeMessages();
            }

            $this->container->setSingleton(OutputContract::class, $output);
        }

        try {
            $this->exit($input, $output);
        } catch (Throwable $exitThrowable) {
            try {
                // A middleware runs here, and the command's code still reaches the shell, so this
                // report is the only trace the failure leaves.
                $this->getOutputFromThrowable($input, $exitThrowable)->writeMessages();
            } catch (Throwable) {
                // The report is the last write, so a failure here leaves no trace to write.
            }
        }

        $exitCode = $output->getExitCode();

        if ($exitCode instanceof ExitCode) {
            $exitCode = $exitCode->value;
        }

        Exiter::exit($exitCode);
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
     * Get the messages that report a throwable when the full report raised.
     *
     * The full report reads the command name from the input, so an input that raises there takes
     * the report with it. These messages read the throwables alone, and `Throwable::getMessage()`
     * is final, so this report raises nothing.
     *
     * @param Throwable $throwable         The throwable
     * @param Throwable $recoveryThrowable The throwable the recovery raised
     *
     * @return MessageContract[]
     */
    protected function getFallbackThrowableMessages(Throwable $throwable, Throwable $recoveryThrowable): array
    {
        return [
            new Banner(new ErrorMessage('Cli Server Error:')),
            new NewLine(),
            new ErrorMessage('Message:'),
            new Message(' ' . $throwable->getMessage()),
            new NewLine(),
            new NewLine(),
            new ErrorMessage('Recovery message:'),
            new Message(' ' . $recoveryThrowable->getMessage()),
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
    protected function getThrowableMessages(InputContract $input, Throwable $throwable): array
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
