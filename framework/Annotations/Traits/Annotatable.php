<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations\Traits;

use Closure;

use Valkyrja\Annotations\Annotation;
use Valkyrja\Annotations\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Annotations\Exceptions\InvalidMethodException;
use Valkyrja\Annotations\Exceptions\InvalidClosureException;
use Valkyrja\Annotations\Exceptions\InvalidFunctionException;

/**
 * Class Annotatable
 *
 * @package Valkyrja\Annotations\Traits
 */
trait Annotatable
{
    /**
     * Verify the class and method of an annotation.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     *
     * @return void
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidMethodException
     */
    protected function verifyClassMethod(Annotation $annotation): void
    {
        // If a class and method are set and not callable
        if (
            null !== $annotation->getClass()
            && null !== $annotation->getMethod()
            && ! is_callable(
                [
                    $annotation->getClass(),
                    $annotation->getMethod(),
                ]
            )
        ) {
            // Throw a new invalid method exception
            throw new InvalidMethodException(
                'Method does not exist in class : '
                . $annotation->getName() . ' '
                . $annotation->getClass()
                . '@'
                . $annotation->getMethod()
            );
        }
    }

    /**
     * Verify the function of an annotation.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     *
     * @return void
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidFunctionException
     */
    protected function verifyFunction(Annotation $annotation): void
    {
        // If a function is set and is not callable
        if (null !== $annotation->getFunction() && ! is_callable($annotation->getFunction())) {
            // Throw a new invalid function exception
            throw new InvalidFunctionException(
                'Function is not callable for : '
                . $annotation->getName() . ' '
                . $annotation->getFunction()
            );
        }
    }

    /**
     * Verify the closure of an annotation.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     *
     * @return void
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidClosureException
     */
    protected function verifyClosure(Annotation $annotation): void
    {
        // If a closure is set and is not callable
        if (null !== $annotation->getClosure() && ! $annotation->getClosure() instanceof Closure) {
            // Throw a new invalid closure exception
            throw new InvalidClosureException(
                'Closure is not valid for : '
                . $annotation->getName()
            );
        }
    }

    /**
     * Verify the annotation's dispatch capabilities.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     *
     * @return void
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Annotations\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Annotations\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Annotations\Exceptions\InvalidMethodException
     */
    protected function verifyDispatch(Annotation $annotation): void
    {
        // If a function, closure, and class or method are not set
        if (
            null === $annotation->getFunction()
            && null === $annotation->getClosure()
            && (
                null === $annotation->getClass()
                || null === $annotation->getMethod()
            )
        ) {
            // Throw a new invalid dispatch capability exception
            throw new InvalidDispatchCapabilityException(
                'Dispatch capability is not valid for : '
                . $annotation->getName()
            );
        }

        $this->verifyClassMethod($annotation);
        $this->verifyFunction($annotation);
        $this->verifyClosure($annotation);
    }

    /**
     * Dispatch a class method.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return mixed
     */
    protected function dispatchClassMethod(Annotation $annotation, array $arguments = [])
    {
        // If a class and method are set
        if (null !== $annotation->getClass() && null !== $annotation->getMethod()) {
            // Set the class through the container
            $class = container()->get($annotation->getClass());
            $method = $annotation->getMethod();
            $dispatch = null;

            // Before dispatch helper
            $this->beforeClassMethodDispatch($class, $method, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $dispatch = $class->$method(...$arguments);
            }

            // If there is no dispatch
            if (null === $dispatch) {
                // Dispatch without unpacking
                $dispatch = $class->$method();
            }

            // After dispatch helper
            $this->afterClassMethodDispatch($class, $method, $dispatch);

            return $dispatch;
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
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return mixed
     */
    protected function dispatchFunction(Annotation $annotation, array $arguments = [])
    {
        // If a function is set
        if (null !== $annotation->getFunction()) {
            $function = $annotation->getFunction();
            $dispatch = null;

            // Before dispatch helper
            $this->beforeFunctionDispatch($function, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $dispatch = $function(...$arguments);
            }

            // If there is no dispatch
            if (null === $dispatch) {
                // Dispatch without unpacking
                $dispatch = $function();
            }

            // After dispatch helper
            $this->afterFunctionDispatch($function, $dispatch);

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
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return mixed
     */
    protected function dispatchClosure(Annotation $annotation, array $arguments = [])
    {
        // If a closure is set
        if (null !== $annotation->getClosure()) {
            $closure = $annotation->getClosure();
            $dispatch = null;

            // Before dispatch helper
            $this->beforeClosureDispatch($closure, $arguments);

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $dispatch = $closure(...$arguments);
            }

            // If there is no dispatch
            if (null === $dispatch) {
                // Dispatch without unpacking
                $dispatch = $closure();
            }

            // After dispatch helper
            $this->afterClosureDispatch($closure, $dispatch);

            return $dispatch;
        }

        return null;
    }

    /**
     * Before the closure has dispatched.
     *
     * @param string $closure  The function
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
     * @param string $closure The function
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
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return mixed
     */
    protected function dispatchCallable(Annotation $annotation, array $arguments = [])
    {
        // Attempt to dispatch the annotation using the class and method
        $dispatch = $this->dispatchClassMethod($annotation, $arguments);

        // If there is no dispatch
        if (! $dispatch) {
            // Attempt to dispatch the annotation using the function
            $dispatch = $this->dispatchFunction($annotation, $arguments);
        }

        // If there is still no dispatch
        if (! $dispatch) {
            // Attempt to dispatch the annotation using the closure
            $dispatch = $this->dispatchClosure($annotation, $arguments);
        }

        return $dispatch;
    }
}
