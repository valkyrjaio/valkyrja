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

namespace Valkyrja\Dispatcher\Validator\Contract;

use Valkyrja\Dispatcher\Exception\InvalidClosureException;
use Valkyrja\Dispatcher\Exception\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exception\InvalidFunctionException;
use Valkyrja\Dispatcher\Exception\InvalidMethodException;
use Valkyrja\Dispatcher\Exception\InvalidPropertyException;
use Valkyrja\Dispatcher\Model\Contract\Dispatch;

/**
 * Interface Validator.
 *
 * @author Melech Mizrachi
 */
interface Validator
{
    /**
     * Validate the dispatch's dispatch capabilities.
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
    public function dispatch(Dispatch $dispatch): void;

    /**
     * Validate the class and method of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidMethodException
     *
     * @return void
     */
    public function classMethod(Dispatch $dispatch): void;

    /**
     * Validate the class and property of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function classProperty(Dispatch $dispatch): void;

    /**
     * Validate the function of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidFunctionException
     *
     * @return void
     */
    public function func(Dispatch $dispatch): void;
}
