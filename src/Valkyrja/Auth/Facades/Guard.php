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

namespace Valkyrja\Auth\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Auth\Guard as Contract;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Guard.
 *
 * @author Melech Mizrachi
 */
class Guard extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->container()->getSingleton(Contract::class);
    }
}
