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

use function is_callable;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Enums\Constant;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;

/**
 * Trait CallableDispatcher.
 *
 * @author Melech Mizrachi
 */
trait CallableDispatcher
{
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
        if ($this->isInvalidFunction($dispatch)) {
            // Throw a new invalid function exception
            throw new InvalidFunctionException(
                'Function is not callable for : '
                . $dispatch->getName() . ' '
                . $dispatch->getFunction()
            );
        }
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
        if (! $this->hasValidFunction($dispatch)) {
            return null;
        }

        $function  = $dispatch->getFunction();
        $arguments = $arguments ?? [];
        $response  = $function(...$arguments);

        return $response ?? Constant::DISPATCHED;
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
        if (! $this->hasValidClosure($dispatch)) {
            return null;
        }

        $closure   = $dispatch->getClosure();
        $arguments = $arguments ?? [];
        $response  = $closure(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Determine if a dispatch's function is invalid.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isInvalidFunction(Dispatch $dispatch): bool
    {
        return $this->hasValidFunction($dispatch) && ! is_callable($dispatch->getFunction());
    }

    /**
     * Determine if a dispatch has a function set.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function hasValidFunction(Dispatch $dispatch): bool
    {
        return null !== $dispatch->getFunction();
    }

    /**
     * Determine if a dispatch has a closure set.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function hasValidClosure(Dispatch $dispatch): bool
    {
        return null !== $dispatch->getClosure();
    }
}
