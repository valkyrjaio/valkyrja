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
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
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
            $output = $this->getOutputFromThrowable($input, $throwable);
            $output = $this->throwableCaughtHandler->throwableCaught($input, $output, $throwable);
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
            $output->writeMessages();
        } catch (Throwable $throwable) {
            $output = $this->getOutputFromThrowable($input, $throwable);
            $output = $this->throwableCaughtHandler->throwableCaught($input, $output, $throwable);

            try {
                $output->writeMessages();
            } catch (Throwable $recoveryThrowable) {
                // A middleware can return an output whose destination is the one that just failed.
                $output = $this->getOutputFromThrowable($input, $recoveryThrowable);

                $output->writeMessages();
            }
        }

        $this->exit($input, $output);

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
        $commandName = $input->getCommandName();

        return $this->outputFactory
            ->createOutput(exitCode: ExitCode::ERROR)
            ->withMessages(
                new Banner(new ErrorMessage('Cli Server Error:')),
                new NewLine(),
                new ErrorMessage('Command:'),
                new Message(" $commandName"),
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Message:'),
                new Message(' ' . $throwable->getMessage()),
            );
    }
}
