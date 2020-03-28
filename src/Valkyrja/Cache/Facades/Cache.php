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

namespace Valkyrja\Cache\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Cache\Store;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 *
 * @method static Store getStore(string $name = null)
 */
class Cache extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->cache();
    }
}
