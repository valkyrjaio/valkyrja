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

namespace Valkyrja\Dispatcher\Dispatchers;

use Valkyrja\Application\Application;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Constants\Constant;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher as Contract;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Support\Provider\Traits\Provides;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements Contract
{
    use CallableDispatcher;
    use ClassDispatcher;
    use Provides;

    /**
     * Dispatcher constructor.
     *
     * @param Container $container The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $dispatcher = new static($app->container());

        $app->setDispatcher($dispatcher);
    }

    /**
     * Verify the dispatch's dispatch capabilities.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function verifyDispatch(Dispatch $dispatch): void
    {
        $this->verifyNotEmptyDispatch($dispatch);
        $this->verifyClassMethod($dispatch);
        $this->verifyClassProperty($dispatch);
        $this->verifyFunction($dispatch);
    }

    /**
     * Dispatch a callable.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatch(Dispatch $dispatch, array $arguments = null)
    {
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments);

        // Attempt to dispatch the dispatch
        $response = $this->dispatchClassMethod($dispatch, $arguments)
            ?? $this->dispatchClassProperty($dispatch)
            ?? $this->dispatchClass($dispatch, $arguments)
            ?? $this->dispatchFunction($dispatch, $arguments)
            ?? $this->dispatchClosure($dispatch, $arguments);
        // TODO: Add Constant and Variable ability

        // If the response was initially null and we added the dispatched text to avoid calling each subsequent
        // dispatcher thereafter so let's reset it to null
        return $response !== Constant::DISPATCHED ? $response : null;
    }

    /**
     * Verify that a dispatch has something to dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidDispatchCapabilityException
     *
     * @return void
     */
    protected function verifyNotEmptyDispatch(Dispatch $dispatch): void
    {
        // If a function, closure, and class or method are not set
        if ($this->isEmptyDispatch($dispatch)) {
            // Throw a new invalid dispatch capability exception
            throw new InvalidDispatchCapabilityException(
                'Dispatch capability is not valid for : '
                . $dispatch->getName()
            );
        }
    }

    /**
     * Check if a dispatch is empty.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isEmptyDispatch(Dispatch $dispatch): bool
    {
        return null === $dispatch->getFunction()
            && null === $dispatch->getClosure()
            && null === $dispatch->getClass()
            && null === $dispatch->getMethod()
            && null === $dispatch->getProperty();
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return array
     */
    protected function getArguments(Dispatch $dispatch, array $arguments = null): ?array
    {
        // Get either the arguments passed or from the dispatch model
        $arguments = $arguments ?? $dispatch->getArguments();

        // Set the listener arguments to a new blank array
        $dependencies = $this->getDependencies($dispatch);

        // If there are no arguments
        if (null === $arguments) {
            // Return the dependencies only
            return $dependencies;
        }

        // Iterate through the arguments
        foreach ($arguments as $argument) {
            // Append the argument to the arguments list
            $dependencies[] = $this->getArgumentValue($argument);
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return array
     */
    protected function getDependencies(Dispatch $dispatch): ?array
    {
        $dependencies = [];

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            $context = $dispatch->getClass() ?? $dispatch->getFunction() ?? '';
            $member  = $dispatch->getMethod();

            $container        = $this->container;
            $containerContext = $container->withContext($context, $member);

            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // If there is a context dependency
                if ($containerContext->has($dependency)) {
                    // Set the context dependency from the container
                    $dependencies[] = $containerContext->get($dependency, []);

                    continue;
                }

                // Set the dependency from the container
                $dependencies[] = $container->get($dependency, []);
            }
        }

        return $dependencies;
    }

    /**
     * Get argument value.
     *
     * @param mixed $argument The argument
     *
     * @return mixed
     */
    protected function getArgumentValue($argument)
    {
        if ($argument instanceof Dispatch) {
            // Dispatch the argument and set the results to the argument
            $argument = $this->dispatch($argument);
        }

        return $argument;
    }
}
