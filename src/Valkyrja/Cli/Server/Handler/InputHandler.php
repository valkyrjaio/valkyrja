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

namespace Valkyrja\Cli\Server\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Data\Config as InteractionConfig;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract as Contract;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;

class InputHandler implements Contract
{
    public function __construct(
        protected ContainerContract $container = new Container(),
        protected RouterContract $router = new Router(),
        protected InputReceivedHandlerContract $inputReceivedHandler = new InputReceivedHandler(),
        protected ThrowableCaughtHandlerContract $throwableCaughtHandler = new ThrowableCaughtHandler(),
        protected ExitedHandlerContract $exitedHandler = new ExitedHandler(),
        protected InteractionConfig $interactionConfig = new InteractionConfig(),
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
        // Dispatch the exited middleware
        $this->exitedHandler->exited($input, $output);
    }

    /**
     * Run the handler.
     *
     *
     */
    #[Override]
    public function run(InputContract $input): void
    {
        $output = $this->handle($input);

        $output->writeMessages();

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
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Line:'),
                new Message(' ' . ((string) $throwable->getLine())),
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Trace:'),
                new NewLine(),
                new Message($throwable->getTraceAsString() . "\n"),
            );
    }
}
