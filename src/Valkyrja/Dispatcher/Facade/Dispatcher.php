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

namespace Valkyrja\Dispatcher\Facade;

use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Model\Contract\Dispatch;
use Valkyrja\Facade\ContainerFacade;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 *
 * @method static void  verifyDispatch(Dispatch $dispatch)
 * @method static void  verifyClassMethod(Dispatch $dispatch)
 * @method static void  verifyClassProperty(Dispatch $dispatch)
 * @method static void  verifyFunction(Dispatch $dispatch)
 * @method static mixed dispatchClassMethod(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchClassProperty(Dispatch $dispatch)
 * @method static mixed dispatchClass(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchFunction(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatchClosure(Dispatch $dispatch, array $arguments = null)
 * @method static mixed dispatch(Dispatch $dispatch, array $arguments = null)
 */
class Dispatcher extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
