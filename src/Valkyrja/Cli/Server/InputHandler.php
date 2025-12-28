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

namespace Valkyrja\Cli\Server;

use Override;
use Throwable;
use Valkyrja\Cli\Command\VersionCommand;
use Valkyrja\Cli\Interaction\Config as InteractionConfig;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Data\Option\NoInteractionOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\QuietOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\SilentOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\VersionOptionParameter;
use Valkyrja\Cli\Routing\Dispatcher\Contract\Router;
use Valkyrja\Cli\Server\Contract\InputHandler as Contract;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Support\Exiter;

/**
 * Class InputHandler.
 *
 * @author Melech Mizrachi
 */
class InputHandler implements Contract
{
    /**
     * RequestHandler constructor.
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Router $router = new \Valkyrja\Cli\Routing\Dispatcher\Router(),
        protected InputReceivedHandler $inputReceivedHandler = new Middleware\Handler\InputReceivedHandler(),
        protected ThrowableCaughtHandler $throwableCaughtHandler = new Middleware\Handler\ThrowableCaughtHandler(),
        protected ExitedHandler $exitedHandler = new Middleware\Handler\ExitedHandler(),
        protected InteractionConfig $interactionConfig = new InteractionConfig(),
    ) {
    }

    /**
     * Handle the input.
     *
     * @param Input $input The input
     *
     * @return Output
     */
    #[Override]
    public function handle(Input $input): Output
    {
        try {
            $this->setIsInteractive($input);
            $this->setIsQuiet($input);
            $this->setIsSilent($input);

            if (
                $input->hasOption(VersionOptionParameter::SHORT_NAME)
                || $input->hasOption(VersionOptionParameter::NAME)
            ) {
                $input = $input->withCommandName(VersionCommand::NAME);
            }

            $output = $this->dispatchRouter($input);
        } catch (Throwable $throwable) {
            $output = $this->getOutputFromThrowable($input, $throwable);
            $output = $this->throwableCaughtHandler->throwableCaught($input, $output, $throwable);
        }

        // Set the returned output in the container
        $this->container->setSingleton(Output::class, $output);

        return $output;
    }

    /**
     * Handle exiting the handler.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @return void
     */
    #[Override]
    public function exit(Input $input, Output $output): void
    {
        // Dispatch the exited middleware
        $this->exitedHandler->exited($input, $output);
    }

    /**
     * Run the handler.
     *
     * @param Input $input
     *
     * @return void
     */
    #[Override]
    public function run(Input $input): void
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
     * Set the interactivity.
     *
     * @param Input $input The input
     *
     * @return void
     */
    protected function setIsInteractive(Input $input): void
    {
        if (
            $input->hasOption(NoInteractionOptionParameter::SHORT_NAME)
            || $input->hasOption(NoInteractionOptionParameter::NAME)
        ) {
            $this->interactionConfig->isInteractive = false;
        }
    }

    /**
     * Set whether output is quiet.
     *
     * @param Input $input The input
     *
     * @return void
     */
    protected function setIsQuiet(Input $input): void
    {
        if (
            $input->hasOption(QuietOptionParameter::SHORT_NAME)
            || $input->hasOption(QuietOptionParameter::NAME)
        ) {
            $this->interactionConfig->isQuiet = true;
        }
    }

    /**
     * Set whether the output is entirely silent.
     *
     * @param Input $input The input
     *
     * @return void
     */
    protected function setIsSilent(Input $input): void
    {
        if (
            $input->hasOption(SilentOptionParameter::SHORT_NAME)
            || $input->hasOption(SilentOptionParameter::NAME)
        ) {
            $this->interactionConfig->isSilent = true;
        }
    }

    /**
     * Dispatch the input via the router.
     *
     * @param Input $input The input
     *
     * @return Output
     */
    protected function dispatchRouter(Input $input): Output
    {
        // Set the request object in the container
        $this->container->setSingleton(Input::class, $input);

        // Dispatch the before input received middleware
        $inputAfterMiddleware = $this->inputReceivedHandler->inputReceived($input);

        // If the return value after middleware is a response return it
        if ($inputAfterMiddleware instanceof Output) {
            return $inputAfterMiddleware;
        }

        // Set the returned request in the container
        $this->container->setSingleton(Input::class, $inputAfterMiddleware);

        return $this->router->dispatch($inputAfterMiddleware);
    }

    /**
     * Get an output from a throwable.
     *
     * @param Input     $input     The input
     * @param Throwable $throwable The throwable
     *
     * @return Output
     */
    protected function getOutputFromThrowable(Input $input, Throwable $throwable): Output
    {
        $commandName = $input->getCommandName();

        return (new \Valkyrja\Cli\Interaction\Output\Output(
            exitCode: ExitCode::ERROR
        ))
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
