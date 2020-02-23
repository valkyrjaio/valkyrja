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

namespace Valkyrja\Console\Enums;

use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Console\Commands\CacheAll;
use Valkyrja\Console\Commands\CommandsList;
use Valkyrja\Console\Commands\CommandsListForBash;
use Valkyrja\Console\Commands\ConsoleCache;
use Valkyrja\Console\Commands\Optimize;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\Event\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutesCache;
use Valkyrja\Routing\Commands\RoutesList;

/**
 * Enum Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider extends Enum
{
    public const CONFIG_CACHE_COMMAND           = ConfigCache::class;
    public const CONFIG_ALL_COMMAND             = CacheAll::class;
    public const COMMANDS_LIST_COMMAND          = CommandsList::class;
    public const CONSOLE_CACHE_COMMAND          = ConsoleCache::class;
    public const COMMANDS_LIST_FOR_BASH_COMMAND = CommandsListForBash::class;
    public const OPTIMIZE_COMMAND               = Optimize::class;
    public const CONTAINER_CACHE_COMMAND        = ContainerCache::class;
    public const EVENTS_CACHE_COMMAND           = EventsCache::class;
    public const ROUTES_CACHE_COMMAND           = RoutesCache::class;
    public const ROUTES_LIST_COMMAND            = RoutesList::class;
}
