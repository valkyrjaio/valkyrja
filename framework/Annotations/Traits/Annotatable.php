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
     * Dispatch a class method event listener.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return void
     */
    protected function dispatchClassMethod(Annotation $annotation, array $arguments = []): void
    {
        // If a class and method are set
        if (null !== $annotation->getClass() && null !== $annotation->getMethod()) {
            $class = $annotation->getClass();
            $method = $annotation->getMethod();

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $class->$method(...$arguments);
            }
            // Otherwise no need to unpack
            else {
                // Dispatch
                $class->$method();
            }
        }
    }

    /**
     * Dispatch a function event listener.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return void
     */
    protected function dispatchFunction(Annotation $annotation, array $arguments = []): void
    {
        // If a function is set
        if (null !== $annotation->getFunction()) {
            $function = $annotation->getFunction();

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $function(...$arguments);
            }
            // Otherwise no need to unpack
            else {
                // Dispatch
                $function();
            }
        }
    }

    /**
     * Dispatch a closure event listener.
     *
     * @param \Valkyrja\Annotations\Annotation $annotation The annotation
     * @param array                            $arguments  The arguments
     *
     * @return void
     */
    protected function dispatchClosure(Annotation $annotation, array $arguments = []): void
    {
        // If a closure is set
        if (null !== $annotation->getClosure()) {
            $closure = $annotation->getClosure();

            // If there are arguments
            if ($arguments) {
                // Unpack arguments and dispatch
                $closure(...$arguments);
            }
            // Otherwise no need to unpack
            else {
                // Dispatch
                $closure();
            }
        }
    }
}
