<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher\Dispatchers;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Container\Container;
use Valkyrja\Container\Service;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher as DispatcherContract;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements DispatcherContract
{
    /**
     * The return value to use when a dispatch was successful
     * but no data was returned from the dispatch.
     * This avoids having to check each and every
     * other type of dispatch down the chain.
     *
     * @var string
     */
    protected string $DISPATCHED = 'dispatcher.dispatched';

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Dispatcher constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app       = $application;
        $this->container = $application->container();
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
     * Verify the class and method of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidMethodException
     *
     * @return void
     */
    public function verifyClassMethod(Dispatch $dispatch): void
    {
        // If a class and method are set and not callable
        if (
            null !== $dispatch->getClass()
            && null !== $dispatch->getMethod()
            && ! method_exists($dispatch->getClass(), $dispatch->getMethod())
        ) {
            // Throw a new invalid method exception
            throw new InvalidMethodException(
                'Method does not exist in class : '
                . $dispatch->getName() . ' '
                . $dispatch->getClass()
                . '@'
                . $dispatch->getMethod()
            );
        }
    }

    /**
     * Verify the class and property of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function verifyClassProperty(Dispatch $dispatch): void
    {
        // If a class and method are set and not callable
        if (
            null !== $dispatch->getClass()
            && null !== $dispatch->getProperty()
            && ! property_exists($dispatch->getClass(), $dispatch->getProperty())
        ) {
            // Throw a new invalid property exception
            throw new InvalidPropertyException(
                'Property does not exist in class : '
                . $dispatch->getName() . ' '
                . $dispatch->getClass()
                . '@'
                . $dispatch->getProperty()
            );
        }
    }

    /**
     * Verify the function of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidFunctionException
     *
     * @return void
     */
    public function verifyFunction(Dispatch $dispatch): void
    {
        // If a function is set and is not callable
        if (null !== $dispatch->getFunction() && ! is_callable($dispatch->getFunction())) {
            // Throw a new invalid function exception
            throw new InvalidFunctionException(
                'Function is not callable for : '
                . $dispatch->getName() . ' '
                . $dispatch->getFunction()
            );
        }
    }

    /**
     * Dispatch a class method.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchClassMethod(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class and method exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getMethod()) {
            return null;
        }

        $class     = $this->getClassFromDispatch($dispatch);
        $method    = $dispatch->getMethod();
        $arguments = $arguments ?? [];
        $response  = $dispatch->isStatic() ? $class::$method(...$arguments) : $class->$method(...$arguments);

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a class property.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    public function dispatchClassProperty(Dispatch $dispatch)
    {
        // Ensure a class and property exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getProperty()) {
            return null;
        }

        $class    = $this->getClassFromDispatch($dispatch);
        $property = $dispatch->getProperty();
        $response = $dispatch->isStatic() ? $class::$$property : $class->{$property};

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a class.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchClass(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class exists before continuing
        if (null === $dispatch->getClass()) {
            return null;
        }

        // If the class is the id then this item is not yet set in the
        // service container so it needs a new instance returned
        if ($dispatch->getClass() === $dispatch->getId()) {
            // Get the class from the dispatcher
            $class     = $dispatch->getClass();
            $arguments = $arguments ?? [];
            $class     = new $class(...$arguments);
        } else {
            // Get the class through the container
            $class = $this->container->get($dispatch->getClass(), $arguments);
        }

        return $class ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a function.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchFunction(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a function exists before continuing
        if (null === $dispatch->getFunction()) {
            return null;
        }

        $function  = $dispatch->getFunction();
        $arguments = $arguments ?? [];
        $response  = $function(...$arguments);

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a closure.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchClosure(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a closure exists before continuing
        if (null === $dispatch->getClosure()) {
            return null;
        }

        $closure   = $dispatch->getClosure();
        $arguments = $arguments ?? [];
        $response  = $closure(...$arguments);

        return $response ?? $this->DISPATCHED;
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
        return $response !== $this->DISPATCHED ? $response : null;
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
        $context   = $dispatch->getClass() ?? $dispatch->getFunction();
        $member    = $dispatch->getProperty() ?? $dispatch->getMethod();

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
            $dependencies[] = $this->getArgumentValue($argument, $context, $member);
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
        $dependencies = null;

        // If the dispatch is static it doesn't need dependencies
        if ($dispatch->isStatic()) {
            return $dependencies;
        }

        $dependencies = [];
        $context      = $dispatch->getClass() ?? $dispatch->getFunction();
        $member       = $dispatch->getProperty() ?? $dispatch->getMethod();

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // Set the dependency in the list
                $dependencies[] = $this->container->get(
                    $dependency,
                    null,
                    $context,
                    $member
                );
            }
        }

        return $dependencies;
    }

    /**
     * Get argument value.
     *
     * @param mixed       $argument The argument
     * @param string|null $context  [optional] The context
     * @param string|null $member   [optional] The context member
     *
     * @return mixed
     */
    protected function getArgumentValue($argument, string $context = null, string $member = null)
    {
        // If the argument is a service
        if ($argument instanceof Service) {
            if (null === $argument->getId()) {
                throw new InvalidArgumentException('Invalid argument.');
            }

            // Dispatch the argument and set the results to the argument
            $argument = $this->container->get(
                $argument->getId(),
                null,
                $context,
                $member
            );
        } // If the argument is a dispatch
        elseif ($argument instanceof Dispatch) {
            // Dispatch the argument and set the results to the argument
            $argument = $this->dispatch($argument);
        }

        return $argument;
    }

    /**
     * Get class from dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed|string|null
     */
    protected function getClassFromDispatch(Dispatch $dispatch)
    {
        return $dispatch->isStatic() ? $dispatch->getClass() : $this->container->get($dispatch->getClass());
    }
}
