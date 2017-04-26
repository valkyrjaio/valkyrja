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
            (null !== $dispatch->getMethod() || null !== $dispatch->getStaticMethod())
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
            && null === $dispatch->getClass()
            && (null === $dispatch->getMethod() && null === $dispatch->getStaticMethod())

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
     * @return array|null
     */
    protected function getDependencies(Dispatch $dispatch):? array
    {
        $dependencies = null;

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // Set the dependency in the list
                $dependencies[] = container()->get($dependency/**, null, $dispatch->getId()*/);
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
        // If a class and method are set
        if (null !== $dispatch->getMethod() || null !== $dispatch->getStaticMethod()) {
            // Set the class through the container
            $class = container()->get($dispatch->getClass());
            $method = $dispatch->getMethod() ?? $dispatch->getStaticMethod();
            $response = null;

            // Before dispatch helper
            $this->beforeClassMethodDispatch($class, $method, $dispatch);

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
     * @param mixed                         $class    The class
     * @param string                        $method   The method
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     */
    protected function beforeClassMethodDispatch($class, string $method, Dispatch $dispatch): void
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
     * Dispatch a class.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch  The dispatch
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchClass(Dispatch $dispatch, array $arguments = null)
    {
        // If a class and method are set
        if (null !== $dispatch->getClass()) {
            // Before dispatch helper
            $this->beforeClassDispatch($dispatch);

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

            // After dispatch helper
            $this->afterClassDispatch($class);

            return $class;
        }

        return null;
    }

    /**
     * Before the class has dispatched.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     */
    protected function beforeClassDispatch(Dispatch $dispatch): void
    {
        // Override this method for custom before dispatch functionality
    }

    /**
     * After the class has dispatched.
     *
     * @param mixed $class The class
     *
     * @return void
     */
    protected function afterClassDispatch($class): void
    {
        // Override this method for custom after dispatch functionality
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
        // If a function is set
        if (null !== $dispatch->getFunction()) {
            $function = $dispatch->getFunction();
            $response = null;

            // Before dispatch helper
            $this->beforeFunctionDispatch($function, $dispatch);

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

            return $response;
        }

        return null;
    }

    /**
     * Before the function has dispatched.
     *
     * @param string                        $function The function
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     */
    protected function beforeFunctionDispatch(string $function, Dispatch $dispatch): void
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
     * @param array|null                    $arguments The arguments
     *
     * @return mixed
     */
    private function dispatchClosure(Dispatch $dispatch, array $arguments = null)
    {
        // If a closure is set
        if (null !== $dispatch->getClosure()) {
            $closure = $dispatch->getClosure();
            $response = null;

            // Before dispatch helper
            $this->beforeClosureDispatch($dispatch);

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
            $this->afterClosureDispatch($response);

            return $response;
        }

        return null;
    }

    /**
     * Before the closure has dispatched.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch The dispatch
     *
     * @return void
     */
    protected function beforeClosureDispatch(Dispatch $dispatch): void
    {
        // Override this method for custom before dispatch functionality
    }

    /**
     * After the closure has dispatched.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return void
     */
    protected function afterClosureDispatch(&$dispatch): void
    {
        // Override this method for custom after dispatch functionality
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

        // Attempt to dispatch the dispatch using the class and method
        $response = $this->dispatchClassMethod($dispatch, $arguments);

        // If there is no dispatch
        if (! $response) {
            // Attempt to dispatch the dispatch using the class
            $response = $this->dispatchClass($dispatch, $arguments);
        }

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
