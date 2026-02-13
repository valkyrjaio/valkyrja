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
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
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
        protected RouteMatchedHandlerContract $routeMatchedHandler = new RouteMatchedHandler(),
        protected RouteNotMatchedHandlerContract $routeNotMatchedHandler = new RouteNotMatchedHandler(),
        protected RouteDispatchedHandlerContract $routeDispatchedHandler = new RouteDispatchedHandler(),
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
        $matchedCommand = $this->attemptToMatchRoute($input);

        // If the command was not matched an output returned
        if ($matchedCommand instanceof OutputContract) {
            // Dispatch RouteNotMatchedMiddleware
            return $this->routeNotMatchedHandler->routeNotMatched(
                input: $input,
                output: $matchedCommand
            );
        }

        return $this->dispatchRoute(
            input: $input,
            route: $matchedCommand
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchRoute(InputContract $input, RouteContract $route): OutputContract
    {
        $route = $this->addParametersToRoute($input, $route);
        // The command has been matched
        $this->routeMatched($route);

        // Dispatch the RouteMatchedMiddleware
        $routeAfterMiddleware = $this->routeMatchedHandler->routeMatched(
            input: $input,
            route: $route
        );

        // If the return value after middleware is an output return it
        if ($routeAfterMiddleware instanceof OutputContract) {
            return $routeAfterMiddleware;
        }

        // Set the command after middleware has potentially modified it in the service container
        $this->container->setSingleton(RouteContract::class, $routeAfterMiddleware);

        $dispatch  = $routeAfterMiddleware->getDispatch();
        $arguments = $dispatch->getArguments();

        // Attempt to dispatch the route using any one of the callable options
        /** @var scalar|object|array<array-key, mixed>|resource|null $output */
        $output = $this->dispatcher->dispatch(
            dispatch: $dispatch,
            arguments: $arguments
        );

        if (! $output instanceof OutputContract) {
            throw new RuntimeException('All commands must return an output');
        }

        return $this->routeDispatchedHandler->routeDispatched(
            input: $input,
            output: $output,
            route: $routeAfterMiddleware
        );
    }

    /**
     * Match a route, or a response if no route exists, from a given input.
     */
    protected function attemptToMatchRoute(InputContract $input): RouteContract|OutputContract
    {
        $commandName = $input->getCommandName();

        // Try to get the command
        $route = $this->collection->get(
            name: $commandName
        );

        // Return the command if it was found
        if ($route !== null) {
            return $route;
        }

        $errorText = "Command `$commandName` was not found.";

        return $this->outputFactory
            ->createOutput(exitCode: ExitCode::ERROR)
            ->withMessages(
                new Banner(new ErrorMessage($errorText))
            );
    }

    /**
     * Add the parameters from the input to the route.
     */
    protected function addParametersToRoute(InputContract $input, RouteContract $route): RouteContract
    {
        $route = $this->addArgumentsToRoute($input, $route);

        return $this->addOptionsToRoute($input, $route);
    }

    /**
     * Add the arguments from the input to the route.
     */
    protected function addArgumentsToRoute(InputContract $input, RouteContract $route): RouteContract
    {
        $arguments          = [...$input->getArguments()];
        $argumentParameters = $route->getArguments();

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

        return $route
            ->withArguments(...$argumentParameters);
    }

    /**
     * Add the options from the input to the route.
     */
    protected function addOptionsToRoute(InputContract $input, RouteContract $route): RouteContract
    {
        $options          = $input->getOptions();
        $optionParameters = [...$route->getOptions()];

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

        return $route
            ->withOptions(...$optionParameters);
    }

    /**
     * Do various stuff after the route has been matched.
     */
    protected function routeMatched(RouteContract $route): void
    {
        $this->routeMatchedHandler->add(...$route->getRouteMatchedMiddleware());
        $this->routeDispatchedHandler->add(...$route->getRouteDispatchedMiddleware());
        $this->throwableCaughtHandler->add(...$route->getThrowableCaughtMiddleware());
        $this->exitedHandler->add(...$route->getExitedMiddleware());

        // Set the found command in the service container
        $this->container->setSingleton(RouteContract::class, $route);
    }
}
