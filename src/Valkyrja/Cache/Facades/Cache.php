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

use Valkyrja\Cache\Cache as Contract;
use Valkyrja\Cache\Driver;
use Valkyrja\Facade\ContainerFacade;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useStore(string $name = null, string $adapter = null)
 */
class Cache extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
