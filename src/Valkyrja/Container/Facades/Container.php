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

namespace Valkyrja\Container\Facades;

use Valkyrja\Facade\ContainerFacade;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 *
 * @method static bool   has(string $id)
 * @method static bool   hasContext(string $id, string $context, string $member = null)
 * @method static void   bind(string $id, string $service)
 * @method static void   bindSingleton(string $id, string $singleton)
 * @method static void   setAlias(string $alias, string $id)
 * @method static void   setContext(string $id, string $context, string $member = null)
 * @method static void   setSingleton(string $id, $singleton)
 * @method static bool   isAlias(string $id)
 * @method static bool   isSingleton(string $id)
 * @method static bool   isProvided(string $id)
 * @method static mixed  get(string $id, array $arguments = [])
 * @method static mixed  makeService(string $id, array $arguments = [])
 * @method static mixed  getSingleton(string $id)
 * @method static string contextServiceId(string $id, string $context, string $member = null)
 */
class Container extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer();
    }
}
