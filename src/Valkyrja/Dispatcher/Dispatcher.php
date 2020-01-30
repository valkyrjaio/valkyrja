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

namespace Valkyrja\Dispatcher;

use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Interface Dispatcher.
 *
 * @author Melech Mizrachi
 */
interface Dispatcher
{
    /**
     * Verify the class and method of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidMethodException
     *
     * @return void
     */
    public function verifyClassMethod(Dispatch $dispatch): void;

    /**
     * Verify the class and property of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function verifyClassProperty(Dispatch $dispatch): void;

    /**
     * Verify the function of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidFunctionException
     *
     * @return void
     */
    public function verifyFunction(Dispatch $dispatch): void;

    /**
     * Verify the closure of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidClosureException
     *
     * @return void
     */
    public function verifyClosure(Dispatch $dispatch): void;

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
    public function verifyDispatch(Dispatch $dispatch): void;

    /**
     * Dispatch a class method.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatchClassMethod(Dispatch $dispatch, array $arguments = null);

    /**
     * Dispatch a class property.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    public function dispatchClassProperty(Dispatch $dispatch);

    /**
     * Dispatch a class.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatchClass(Dispatch $dispatch, array $arguments = null);

    /**
     * Dispatch a function.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatchFunction(Dispatch $dispatch, array $arguments = null);

    /**
     * Dispatch a closure.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatchClosure(Dispatch $dispatch, array $arguments = null);

    /**
     * Dispatch a callable.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    public function dispatchCallable(Dispatch $dispatch, array $arguments = null);
}
