<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher;

use Valkyrja\Application\Application;
use Valkyrja\Container\Service;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class NativeDispatcher implements Dispatcher
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
     * Dispatcher constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
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
            && ! property_exists(
                $dispatch->getClass(),
                $dispatch->getProperty()
            )
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
        if (
            null !== $dispatch->getFunction()
            && ! is_callable($dispatch->getFunction())
        ) {
            // Throw a new invalid function exception
            throw new InvalidFunctionException(
                'Function is not callable for : '
                . $dispatch->getName() . ' '
                . $dispatch->getFunction()
            );
        }
    }

    /**
     * Verify the closure of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidClosureException
     *
     * @return void
     */
    public function verifyClosure(Dispatch $dispatch): void
    {
        // If a closure is set and is not callable
        if (
            null === $dispatch->getFunction()
            && null === $dispatch->getClass()
            && null === $dispatch->getMethod()
            && null === $dispatch->getProperty()
            && null === $dispatch->getClosure()
        ) {
            // Throw a new invalid closure exception
            throw new InvalidClosureException(
                'Closure is not valid for : '
                . $dispatch->getName()
            );
        }
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
     * @throws InvalidClosureException
     *
     * @return void
     */
    public function verifyDispatch(Dispatch $dispatch): void
    {
        // If a function, closure, and class or method are not set
        if (
            null === $dispatch->getFunction()
            && null === $dispatch->getClosure()
            && null === $dispatch->getClass()
            && null === $dispatch->getMethod()
            && null === $dispatch->getProperty()

        ) {
            // Throw a new invalid dispatch capability exception
            throw new InvalidDispatchCapabilityException(
                'Dispatch capability is not valid for : '
                . $dispatch->getName()
            );
        }

        $this->verifyClassMethod($dispatch);
        $this->verifyClassProperty($dispatch);
        $this->verifyFunction($dispatch);
        $this->verifyClosure($dispatch);
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
                $dependencies[] = $this->app->container()->get(
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
            // If the argument is a service
            if ($argument instanceof Service) {
                // Dispatch the argument and set the results to the argument
                $argument = $this->app->container()->get(
                    $argument->getId(),
                    null,
                    $context,
                    $member
                );
            } // If the argument is a dispatch
            elseif ($argument instanceof Dispatch) {
                // Dispatch the argument and set the results to the argument
                $argument = $this->dispatchCallable($argument);
            }

            // Append the argument to the arguments list
            $dependencies[] = $argument;
        }

        return $dependencies;
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
        $response = null;

        // Ensure a class and method exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getMethod()) {
            return $response;
        }

        // Set the class through the container if this isn't a static method
        $class    = $dispatch->isStatic() ? $dispatch->getClass() : $this->app->container()->get($dispatch->getClass());
        $method   = $dispatch->getMethod();
        $response = null;

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            if ($dispatch->isStatic()) {
                $response = $class::$method(...$arguments);
            } else {
                $response = $class->$method(...$arguments);
            }

            return $response ?? $this->DISPATCHED;
        }

        // Dispatch without unpacking
        if ($dispatch->isStatic()) {
            $response = $class::$method();
        } else {
            $response = $class->$method();
        }

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
        $response = null;

        // Ensure a class and property exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getProperty()) {
            return $response;
        }

        $class    = $dispatch->getClass();
        $property = $dispatch->getProperty();

        // If this is a static property
        if ($dispatch->isStatic()) {
            $response = $class::$$property;
        } else {
            // Get the class from the container
            $class    = $this->app->container()->get($dispatch->getClass());
            $response = $class->{$property};
        }

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

        // If the class is the id then this item is not yet set
        // in the service container yet so it needs a new
        // instance returned
        if ($dispatch->getClass() === $dispatch->getId()) {
            // Get the class from the dispatcher
            $class = $dispatch->getClass();

            // If there are argument
            if (null !== $arguments) {
                // Get a new class instance with the arguments
                $class = new $class(...$arguments);
            } else {
                // Otherwise just get a new class instance
                $class = new $class();
            }
        } else {
            // Set the class through the container
            $class = $this->app->container()->get($dispatch->getClass(), $arguments);
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

        $function = $dispatch->getFunction();
        $response = null;

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            $response = $function(...$arguments);
        } else {
            // Dispatch without unpacking
            $response = $function();
        }

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

        $closure  = $dispatch->getClosure();
        $response = null;

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            $response = $closure(...$arguments);
        } else {
            // Dispatch without unpacking
            $response = $closure();
        }

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
    public function dispatchCallable(Dispatch $dispatch, array $arguments = null)
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

        // If the response was initially null and we added the dispatched
        // text to avoid calling each subsequent dispatcher thereafter
        if ($response === $this->DISPATCHED) {
            // Reset the response to null
            $response = null;
        }

        return $response;
    }
}
