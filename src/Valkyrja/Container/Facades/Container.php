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

use Valkyrja\Support\Facade\Facade;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 *
 * @method static bool has(string $serviceId)
 * @method static bool hasContext(string $serviceId, string $context, string $member = null)
 * @method static void bind(string $serviceId, string $service)
 * @method static void bindSingleton(string $serviceId, string $singleton)
 * @method static void setAlias(string $alias, string $serviceId)
 * @method static void setContext(string $serviceId, string $context, string $member = null)
 * @method static void setSingleton(string $serviceId, $singleton)
 * @method static bool isAlias(string $serviceId)
 * @method static bool isSingleton(string $serviceId)
 * @method static bool isProvided(string $serviceId)
 * @method static mixed get(string $serviceId, array $arguments = [])
 * @method static mixed makeService(string $serviceId, array $arguments = [])
 * @method static mixed getSingleton(string $serviceId)
 * @method static string contextServiceId(string $serviceId, string $context, string $member = null)
 */
class Container extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container;
    }
}
