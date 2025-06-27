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

namespace Valkyrja\Cli\Routing;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Collection\Contract\Collection;
use Valkyrja\Cli\Routing\Command\HelpCommand;
use Valkyrja\Cli\Routing\Contract\Router as Contract;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Cli\Routing\Data\Option\HelpOptionParameter;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Exception\RuntimeException;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;

use function in_array;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements Contract
{
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Dispatcher $dispatcher = new \Valkyrja\Dispatcher\Dispatcher(),
        protected Collection $collection = new \Valkyrja\Cli\Routing\Collection\Collection(),
        protected OutputFactory $outputFactory = new \Valkyrja\Cli\Interaction\Factory\OutputFactory(),
        protected ThrowableCaughtHandler&Handler $throwableCaughtHandler = new Middleware\Handler\ThrowableCaughtHandler(),
        protected CommandMatchedHandler&Handler $commandMatchedHandler = new Middleware\Handler\CommandMatchedHandler(),
        protected CommandNotMatchedHandler&Handler $commandNotMatchedHandler = new Middleware\Handler\CommandNotMatchedHandler(),
        protected CommandDispatchedHandler&Handler $commandDispatchedHandler = new Middleware\Handler\CommandDispatchedHandler(),
        protected ExitedHandler&Handler $exitedHandler = new Middleware\Handler\ExitedHandler(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Input $input): Output
    {
        // Attempt to match the command
        $matchedCommand = $this->attemptToMatchCommand($input);

        // If the command was not matched an output returned
        if ($matchedCommand instanceof Output) {
            // Dispatch RouteNotMatchedMiddleware
            return $this->commandNotMatchedHandler->commandNotMatched(
                input: $input,
                output: $matchedCommand
            );
        }

        return $this->dispatchCommand(
            input: $input,
            command: $matchedCommand
        );
    }

    /**
     * @inheritDoc
     */
    public function dispatchCommand(Input $input, Command $command): Output
    {
        // The command has been matched
        $this->commandMatched($command);

        // Dispatch the RouteMatchedMiddleware
        $commandAfterMiddleware = $this->commandMatchedHandler->commandMatched(
            input: $input,
            command: $command
        );

        // If the return value after middleware is an output return it
        if ($commandAfterMiddleware instanceof Output) {
            return $commandAfterMiddleware;
        }

        // Set the command after middleware has potentially modified it in the service container
        $this->container->setSingleton(Command::class, $commandAfterMiddleware);

        $dispatch  = $commandAfterMiddleware->getDispatch();
        $arguments = $dispatch->getArguments();

        // Attempt to dispatch the route using any one of the callable options
        $output = $this->dispatcher->dispatch(
            dispatch: $dispatch,
            arguments: $arguments
        );

        if (! $output instanceof Output) {
            throw new RuntimeException('All commands must return an output');
        }

        return $this->commandDispatchedHandler->commandDispatched(
            input: $input,
            output: $output,
            command: $commandAfterMiddleware
        );
    }

    /**
     * Match a command, or a response if no command exists, from a given input.
     */
    protected function attemptToMatchCommand(Input $input): Command|Output
    {
        $commandName = $input->getCommandName();

        // Try to get the command
        $command = $this->collection->get(
            name: $commandName
        );

        // Return the command if it was found
        if ($command !== null) {
            if (
                $input->hasOption(HelpOptionParameter::NAME)
                || $input->hasOption(HelpOptionParameter::SHORT_NAME)
            ) {
                $command = $this->collection->get(name: HelpCommand::NAME);
                $input   = $input->withOptions(
                    new Option('command', $commandName),
                );

                if ($command === null) {
                    throw new RuntimeException('Help command does not exist');
                }
            }

            return $this->addParametersToCommand($input, $command);
        }

        $errorText = "Command `$commandName` was not found.";

        $output = $this->outputFactory
            ->createOutput(exitCode: ExitCode::ERROR)
            ->withMessages(
                new Banner(new ErrorMessage($errorText))
            );

        return $this->checkCommandNameForTypo($input, $output);
    }

    /**
     * Add the parameters from the input to the command.
     */
    protected function addParametersToCommand(Input $input, Command $command): Command
    {
        $command = $this->addArgumentsToCommand($input, $command);

        return $this->addOptionsToCommand($input, $command);
    }

    /**
     * Add the arguments from the input to the command.
     */
    protected function addArgumentsToCommand(Input $input, Command $command): Command
    {
        $arguments          = [...$input->getArguments()];
        $argumentParameters = $command->getArguments();

        foreach ($argumentParameters as $key => $argumentParameter) {
            $argumentParameterArguments = [];

            // Array arguments must be last, and will take up all the remaining arguments from the input
            if ($argumentParameter->getValueMode() === ArgumentValueMode::ARRAY) {
                $argumentParameterArguments = $arguments;

                $arguments = [];
            } elseif (isset($arguments[$key])) {
                // If not an array type then we should match each argument in order of appearance
                $argumentParameterArguments[] = $arguments[$key];

                unset($arguments[$key]);
            }

            $argumentParameters[$key] = $argumentParameter
                ->withArguments(...$argumentParameterArguments)
                ->validateValues();
        }

        return $command
            ->withArguments(...$argumentParameters);
    }

    /**
     * Add the options from the input to the command.
     */
    protected function addOptionsToCommand(Input $input, Command $command): Command
    {
        $options          = $input->getOptions();
        $optionParameters = [...$command->getOptions()];

        foreach ($optionParameters as $key => $optionParameter) {
            $optionParameterOptions = [];

            foreach ($options as $option) {
                // Add the option only if it matches the name or one of the short names
                if (
                    $optionParameter->getName() === $option->getName()
                    || in_array($option->getName(), $optionParameter->getShortNames(), true)
                ) {
                    $optionParameterOptions[] = $option;
                }
            }

            $optionParameters[$key] = $optionParameter
                ->withOptions(...$optionParameterOptions)
                ->validateValues();
        }

        return $command
            ->withOptions(...$optionParameters);
    }

    /**
     * Check the command name from the input for a typo.
     */
    protected function checkCommandNameForTypo(Input $input, Output $output): Output
    {
        // TODO: See if a command has the same letters typed (in case a typo was made)

        return $output;
    }

    /**
     * Do various stuff after the route has been matched.
     */
    protected function commandMatched(Command $command): void
    {
        // TODO: Add middleware to Command
        // $this->commandMatchedHandler->add(...$command->getCommandMatchedMiddleware());
        // $this->commandDispatchedHandler->add(...$command->getCommandDispatchedMiddleware());
        // $this->throwableCaughtHandler->add(...$command->getThrowableCaughtMiddleware());
        // $this->exitedHandler->add(...$command->getExitedMiddleware());

        // Set the found command in the service container
        $this->container->setSingleton(Command::class, $command);
    }
}
