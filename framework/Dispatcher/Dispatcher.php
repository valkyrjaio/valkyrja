<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher;

use Closure;

use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Events\Listener;

/**
 * Trait Dispatcher
 *
 * @package Valkyrja\Dispatcher
 */
trait Dispatcher
{
    protected $DISPATCHED = 'dispatcher.dispatched';

    /**
     * Verify the class and method of a dispatch.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     */
    protected function verifyClassMethod(Dispatch $dispatch): void
    {
        // If a class and method are set and not callable
        if (
            null !== $dispatch->getClass()
            && null !== $dispatch->getMethod()
            && ! is_callable(
                [
                    $dispatch->getClass(),
                    $dispatch->getMethod(),
                ]
            )
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
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    protected function verifyClassProperty(Dispatch $dispatch): void
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
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     */
    protected function verifyFunction(Dispatch $dispatch): void
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
     * Verify the closure of a dispatch.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     */
    protected function verifyClosure(Dispatch $dispatch): void
    {
        // If a closure is set and is not callable
        if (null !== $dispatch->getClosure() && ! $dispatch->getClosure() instanceof Closure) {
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
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    protected function verifyDispatch(Dispatch $dispatch): void
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
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return array|null
     */
    protected function getDependencies(Dispatch $dispatch):? array
    {
        // If the dispatch is static it doesn't need dependencies
        if ($dispatch->isStatic()) {
            return null;
        }

        $dependencies = null;
        $context = $dispatch->getClass() ?? $dispatch->getFunction();
        $member = $dispatch->getProperty() ?? $dispatch->getMethod();

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // Set the dependency in the list
                $dependencies[] = container()->get($dependency, null, $context, $member);
            }
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return array|null
     */
    protected function getArguments(Dispatch $dispatch, array $arguments = null):? array
    {
        // If the dispatch is static it doesn't need dependencies
        if ($dispatch->isStatic()) {
            return $arguments;
        }

        // Get either the arguments passed or from the dispatch model
        $arguments = $arguments ?? $dispatch->getArguments();

        // If the listener has dependencies
        if (null !== $dispatch->getDependencies()) {
            // Set the listener arguments to a new blank array
            $dependencies = $this->getDependencies($dispatch);

            // If there are no arguments
            if (null === $arguments) {
                // Return the dependencies only
                return $dependencies;
            }

            // Iterate through the arguments
            foreach ($arguments as $argument) {
                // If the argument is a dispatch
                if ($argument instanceof Dispatch) {
                    // Dispatch the argument and set the results to the argument
                    $argument = $this->dispatchCallable($argument);
                }

                // Append the argument to the arguments list
                $dependencies[] = $argument;
            }

            return $dependencies;
        }

        return $arguments;
    }

    /**
     * Dispatch a class method.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchClassMethod(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class and method exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getMethod()) {
            return null;
        }

        // Set the class through the container if this isn't a static method
        $class = $dispatch->isStatic()
            ? $dispatch->getClass()
            : container()->get($dispatch->getClass());
        $method = $dispatch->getMethod();
        $response = null;

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before.class.method', [$class, $method, $dispatch]);
            events()->trigger(
                "dispatch.before.{$dispatch->getClass()}.{$dispatch->getMethod()}",
                [$class, $method, $dispatch]
            );
        }

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            if ($dispatch->isStatic()) {
                $response = $class::$method(...$arguments);
            }
            else {
                $response = $class->$method(...$arguments);
            }
        }
        else {
            // Dispatch without unpacking
            if ($dispatch->isStatic()) {
                $response = $class::$method();
            }
            else {
                $response = $class->$method();
            }
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after.class.method', [$class, $method, $response]);
            events()->trigger(
                "dispatch.after.{$dispatch->getClass()}.{$dispatch->getMethod()}",
                [$class, $method, $response]
            );
        }

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a class property.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    private function dispatchClassProperty(Dispatch $dispatch)
    {
        // Ensure a class and property exist before continuing
        if (null === $dispatch->getClass() || null === $dispatch->getProperty()) {
            return null;
        }

        // Set the class through the container if this isn't a static method
        $class = container()->get($dispatch->getClass());
        $property = $dispatch->getProperty();

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before.class.property', [$class, $property, $dispatch]);
            events()->trigger(
                "dispatch.before.{$dispatch->getClass()}.{$dispatch->getProperty()}",
                [$class, $property, $dispatch]
            );
        }

        $response = $class->{$property};

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after.class.property', [$class, $property, $response]);
            events()->trigger(
                "dispatch.after.{$dispatch->getClass()}.{$dispatch->getProperty()}",
                [$class, $property, $response]
            );
        }

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a class.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchClass(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class exists before continuing
        if (null === $dispatch->getClass()) {
            return null;
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before.class', [$dispatch]);
            events()->trigger("dispatch.before.{$dispatch->getClass()}", [$dispatch]);
        }

        // If the class is the id then this item is
        // not set in the service container yet
        // so it needs to a new instance
        if ($dispatch->getClass() === $dispatch->getId()) {
            // Get the class from the dispatcher
            $class = $dispatch->getClass();

            // If there are argument
            if (null !== $arguments) {
                // Get a new class instance with the arguments
                $class = new $class(...$arguments);
            }
            else {
                // Otherwise just get a new class instance
                $class = new $class();
            }
        }
        else {
            // Set the class through the container
            $class = container()->get($dispatch->getClass(), $arguments);
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after.class', [$class]);
            events()->trigger("dispatch.after.{$dispatch->getClass()}", [$class]);
        }

        return $class ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a function.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchFunction(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a function exists before continuing
        if (null === $dispatch->getFunction()) {
            return null;
        }

        $function = $dispatch->getFunction();
        $response = null;

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before.function', [$function, $dispatch]);
            events()->trigger("dispatch.before.{$dispatch->getFunction()}", [$function, $dispatch]);
        }

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            $response = $function(...$arguments);
        }
        else {
            // Dispatch without unpacking
            $response = $function();
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after.function', [$function, $response]);
            events()->trigger("dispatch.after.{$dispatch->getFunction()}", [$function, $response]);
        }

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a closure.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchClosure(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a closure exists before continuing
        if (null === $dispatch->getClosure()) {
            return null;
        }

        $closure = $dispatch->getClosure();
        $response = null;

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before.closure', [$dispatch]);
        }

        // If there are arguments
        if (null !== $arguments) {
            // Unpack arguments and dispatch
            $response = $closure(...$arguments);
        }
        else {
            // Dispatch without unpacking
            $response = $closure();
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after.closure', [$response]);
        }

        return $response ?? $this->DISPATCHED;
    }

    /**
     * Dispatch a callable.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchCallable(Dispatch $dispatch, array $arguments = null)
    {
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments);

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // Before dispatch event
            events()->trigger('dispatch.before', [$dispatch, $arguments]);
        }

        // Attempt to dispatch the dispatch
        $response = $this->dispatchClassMethod($dispatch, $arguments)
            ?? $this->dispatchClassProperty($dispatch)
            ?? $this->dispatchClass($dispatch, $arguments)
            ?? $this->dispatchFunction($dispatch, $arguments)
            ?? $this->dispatchClosure($dispatch, $arguments);
        // TODO: Add Constant and Variable ability

        // If the response was initially null and we added the dispatched text to avoid
        // calling each subsequent dispatcher thereafter
        if ($response === $this->DISPATCHED) {
            // Reset the response to null
            $response = null;
        }

        // Avoid infinite recursion
        if (! $dispatch instanceof Listener) {
            // After dispatch event
            events()->trigger('dispatch.after', [$dispatch, $response]);
        }

        return $response;
    }
}
