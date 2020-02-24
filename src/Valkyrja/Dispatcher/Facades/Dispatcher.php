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

namespace Valkyrja\Dispatcher\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 *
 * @method static void verifyDispatch(Dispatch $dispatch)
 * @method static void verifyClassMethod(Dispatch $dispatch)
 * @method static void verifyClassProperty(Dispatch $dispatch)
 * @method static void verifyFunction(Dispatch $dispatch)
 * @method static mixed dispatchClassMethod(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchClassProperty(Dispatch $dispatch)
 * @method static mixed dispatchClass(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchFunction(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchClosure(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatch(Dispatch $dispatch, array $arguments = null)
 */
class Dispatcher extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->dispatcher();
    }
}
