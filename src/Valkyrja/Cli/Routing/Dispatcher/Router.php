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

namespace Valkyrja\Cli\Routing\Dispatcher;

use Override;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\RuntimeException;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;

use function in_array;

class Router implements RouterContract
{
    public function __construct(
        protected ContainerContract $container = new Container(),
        protected DispatcherContract $dispatcher = new Dispatcher(),
        protected CollectionContract $collection = new Collection(),
        protected OutputFactoryContract $outputFactory = new OutputFactory(),
        protected ThrowableCaughtHandlerContract $throwableCaughtHandler = new ThrowableCaughtHandler(),
        protected RouteMatchedHandlerContract $commandMatchedHandler = new RouteMatchedHandler(),
        protected RouteNotMatchedHandlerContract $commandNotMatchedHandler = new RouteNotMatchedHandler(),
        protected RouteDispatchedHandlerContract $commandDispatchedHandler = new RouteDispatchedHandler(),
        protected ExitedHandlerContract $exitedHandler = new ExitedHandler(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatch(InputContract $input): OutputContract
    {
        // Attempt to match the command
        $matchedCommand = $this->attemptToMatchCommand($input);

        // If the command was not matched an output returned
        if ($matchedCommand instanceof OutputContract) {
            // Dispatch RouteNotMatchedMiddleware
            return $this->commandNotMatchedHandler->routeNotMatched(
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
    #[Override]
    public function dispatchCommand(InputContract $input, RouteContract $command): OutputContract
    {
        // The command has been matched
        $this->commandMatched($command);

        // Dispatch the RouteMatchedMiddleware
        $commandAfterMiddleware = $this->commandMatchedHandler->routeMatched(
            input: $input,
            route: $command
        );

        // If the return value after middleware is an output return it
        if ($commandAfterMiddleware instanceof OutputContract) {
            return $commandAfterMiddleware;
        }

        // Set the command after middleware has potentially modified it in the service container
        $this->container->setSingleton(RouteContract::class, $commandAfterMiddleware);

        $dispatch  = $commandAfterMiddleware->getDispatch();
        $arguments = $dispatch->getArguments();

        // Attempt to dispatch the route using any one of the callable options
        $output = $this->dispatcher->dispatch(
            dispatch: $dispatch,
            arguments: $arguments
        );

        if (! $output instanceof OutputContract) {
            throw new RuntimeException('All commands must return an output');
        }

        return $this->commandDispatchedHandler->routeDispatched(
            input: $input,
            output: $output,
            route: $commandAfterMiddleware
        );
    }

    /**
     * Match a command, or a response if no command exists, from a given input.
     */
    protected function attemptToMatchCommand(InputContract $input): RouteContract|OutputContract
    {
        $commandName = $input->getCommandName();

        // Try to get the command
        $command = $this->collection->get(
            name: $commandName
        );

        // Return the command if it was found
        if ($command !== null) {
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
    protected function addParametersToCommand(InputContract $input, RouteContract $command): RouteContract
    {
        $command = $this->addArgumentsToCommand($input, $command);

        return $this->addOptionsToCommand($input, $command);
    }

    /**
     * Add the arguments from the input to the command.
     */
    protected function addArgumentsToCommand(InputContract $input, RouteContract $command): RouteContract
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
    protected function addOptionsToCommand(InputContract $input, RouteContract $command): RouteContract
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
    protected function checkCommandNameForTypo(InputContract $input, OutputContract $output): RouteContract|OutputContract
    {
        $name = $input->getCommandName();

        $commands = [];

        foreach ($this->collection->all() as $command) {
            similar_text($command->getName(), $name, $percent);

            if ($percent >= 60) {
                $commands[] = $command;
            }
        }

        if ($commands !== []) {
            return $this->askToRunSimilarCommands($output, $commands);
        }

        return $output;
    }

    /**
     * Ask the user if they want to run similar commands.
     *
     * @param RouteContract[] $commands The list of commands
     */
    protected function askToRunSimilarCommands(OutputContract $output, array $commands): RouteContract|OutputContract
    {
        $command = null;

        $commandNames = array_map(static fn (RouteContract $command) => $command->getName(), $commands);

        $output = $output
            ->withAddedMessages(
                new NewLine(),
                new Question(
                    'Did you mean to run one of the following commands?',
                    static function (OutputContract $output, AnswerContract $answer) use (&$command, $commands): OutputContract {
                        $response = $answer->getUserResponse();
                        $command  = $response !== 'no'
                            ? array_filter($commands, static fn (RouteContract $command): bool => $command->getName() === $response)[0] ?? null
                            : null;

                        return $output;
                    },
                    new Answer(
                        defaultResponse: 'no',
                        allowedResponses: $commandNames
                    ),
                ),
            )
            ->writeMessages();

        return $command
            ?? $output;
    }

    /**
     * Do various stuff after the route has been matched.
     */
    protected function commandMatched(RouteContract $command): void
    {
        $this->commandMatchedHandler->add(...$command->getCommandMatchedMiddleware());
        $this->commandDispatchedHandler->add(...$command->getCommandDispatchedMiddleware());
        $this->throwableCaughtHandler->add(...$command->getThrowableCaughtMiddleware());
        $this->exitedHandler->add(...$command->getExitedMiddleware());

        // Set the found command in the service container
        $this->container->setSingleton(RouteContract::class, $command);
    }
}
