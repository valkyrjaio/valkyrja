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

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 *
 * @method static void setAlias(string $alias, string $serviceId)
 * @method static void bind(Service $service, bool $verify = true)
 * @method static void setContext(ServiceContext $serviceContext)
 * @method static void setSingleton(string $serviceId, $singleton)
 * @method static bool has(string $serviceId)
 * @method static bool hasContext(string $serviceId, string $context, string $member = null)
 * @method static bool isAlias(string $serviceId)
 * @method static bool isSingleton(string $serviceId)
 * @method static bool isProvided(string $serviceId)
 * @method static mixed get(string $serviceId, array $arguments = null, string $context = null, string $member = null)
 * @method static mixed makeService(string $serviceId, array $arguments = null)
 * @method static mixed getSingleton(string $serviceId)
 * @method static mixed getProvided(string $serviceId, array $arguments = null, string $context = null, string $member = null)
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
        return Valkyrja::app()->container();
    }
}
