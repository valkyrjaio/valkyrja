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

/**
 * Trait Dispatcher
 *
 * @package Valkyrja\Dispatcher
 */
trait Dispatcher
{
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
            && (null !== $dispatch->getMethod() || null !== $dispatch->getStaticMethod())
            && ! is_callable(
                [
                    $dispatch->getClass(),
                    $dispatch->getMethod() ?? $dispatch->getStaticMethod(),
                ]
            )
        ) {
            // Throw a new invalid method exception
            throw new InvalidMethodException(
                'Method does not exist in class : '
                . $dispatch->getName() . ' '
                . $dispatch->getClass()
                . '@'
                . ($dispatch->getMethod() ?? $dispatch->getStaticMethod())
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
     */
    protected function verifyDispatch(Dispatch $dispatch): void
    {
        // If a function, closure, and class or method are not set
        if (
            null === $dispatch->getFunction()
            && null === $dispatch->getClosure()
            && (
                null === $dispatch->getClass()
                ||
                (null === $dispatch->getMethod() && null === $dispatch->getStaticMethod())
            )
        ) {
            // Throw a new invalid dispatch capability exception
            throw new InvalidDispatchCapabilityException(
                'Dispatch capability is not valid for : '
                . $dispatch->getName()
            );
        }

        $this->verifyClassMethod($dispatch);
        $this->verifyFunction($dispatch);
        $this->verifyClosure($dispatch);
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return array
     */
    protected function getDependencies(Dispatch $dispatch): array
    {
        $dependencies = [];

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // Set the dependency in the list
                $dependencies[] = container()->get($dependency);
            }
        }

        return $dependencies;
    }

    /**
     * Dispatch a class method.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array                         $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClassMethod(Dispatch $dispatch, array $arguments = [])
    {
        // If a class and method are set
        if (
            null !== $dispatch->getClass()
            && (null !== $dispatch->getMethod() || null !== $dispatch->getStaticMethod())
        ) {
            // Set the class through the container
            $class = container()->get($dispatch->getClass());
            $method = $dispatch->getMethod() ?? $dispatch->getStaticMethod();
            $response = null;

            // Before dispatch helper
            $this->beforeClassMethodDispatch($class, $method, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                if ($dispatch->getStaticMethod()) {
                    $response = $class::$method(...$arguments);
                }
                else {
                    $response = $class->$method(...$arguments);
                }
            }

            // If there is no dispatch
            if (null === $response) {
                // Dispatch without unpacking
                if ($dispatch->getStaticMethod()) {
                    $response = $class::$method();
                }
                else {
                    $response = $class->$method();
                }
            }

            // After dispatch helper
            $this->afterClassMethodDispatch($class, $method, $response);

            return $response;
        }

        return null;
    }

    /**
     * Before the class method has dispatched.
     *
     * @param mixed  $class     The class
     * @param string $method    The method
     * @param array  $arguments The arguments
     *
     * @return void
     */
    protected function beforeClassMethodDispatch($class, string $method, array &$arguments): void
    {
        // Override this method for custom before dispatch functionality
    }

    /**
     * After the class method has dispatched.
     *
     * @param mixed  $class    The class
     * @param string $method   The method
     * @param mixed  $dispatch The dispatch
     *
     * @return void
     */
    protected function afterClassMethodDispatch($class, string $method, &$dispatch): void
    {
        // Override this method for custom after dispatch functionality
    }

    /**
     * Dispatch a function.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array                         $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchFunction(Dispatch $dispatch, array $arguments = [])
    {
        // If a function is set
        if (null !== $dispatch->getFunction()) {
            $function = $dispatch->getFunction();
            $response = null;

            // Before dispatch helper
            $this->beforeFunctionDispatch($function, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $response = $function(...$arguments);
            }

            // If there is no dispatch
            if (null === $response) {
                // Dispatch without unpacking
                $response = $function();
            }

            // After dispatch helper
            $this->afterFunctionDispatch($function, $response);

            return $dispatch;
        }

        return null;
    }

    /**
     * Before the function has dispatched.
     *
     * @param string $function  The function
     * @param array  $arguments The arguments
     *
     * @return void
     */
    protected function beforeFunctionDispatch(string $function, array &$arguments): void
    {
        // Override this method for custom before dispatch functionality
    }

    /**
     * After the function has dispatched.
     *
     * @param string $function The function
     * @param mixed  $dispatch The dispatch
     *
     * @return void
     */
    protected function afterFunctionDispatch(string $function, &$dispatch): void
    {
        // Override this method for custom after dispatch functionality
    }

    /**
     * Dispatch a closure.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array                         $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClosure(Dispatch $dispatch, array $arguments = [])
    {
        // If a closure is set
        if (null !== $dispatch->getClosure()) {
            $closure = $dispatch->getClosure();
            $response = null;

            // Before dispatch helper
            $this->beforeClosureDispatch($closure, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $response = $closure(...$arguments);
            }

            // If there is no dispatch
            if (null === $response) {
                // Dispatch without unpacking
                $response = $closure();
            }

            // After dispatch helper
            $this->afterClosureDispatch($closure, $response);

            return $dispatch;
        }

        return null;
    }

    /**
     * Before the closure has dispatched.
     *
     * @param string $closure   The function
     * @param array  $arguments The arguments
     *
     * @return void
     */
    protected function beforeClosureDispatch(string $closure, array &$arguments): void
    {
        // Override this method for custom before dispatch functionality
    }

    /**
     * After the closure has dispatched.
     *
     * @param string $closure  The function
     * @param mixed  $dispatch The dispatch
     *
     * @return void
     */
    protected function afterClosureDispatch(string $closure, &$dispatch): void
    {
        // Override this method for custom after dispatch functionality
    }

    /**
     * Dispatch a callable.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array                         $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchCallable(Dispatch $dispatch, array $arguments = [])
    {
        // Attempt to dispatch the dispatch using the class and method
        $response = $this->dispatchClassMethod($dispatch, $arguments);

        // If there is no dispatch
        if (! $response) {
            // Attempt to dispatch the dispatch using the function
            $response = $this->dispatchFunction($dispatch, $arguments);
        }

        // If there is still no dispatch
        if (! $response) {
            // Attempt to dispatch the dispatch using the closure
            $response = $this->dispatchClosure($dispatch, $arguments);
        }

        return $response;
    }
}
