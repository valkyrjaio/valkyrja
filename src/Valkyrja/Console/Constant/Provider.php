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

namespace Valkyrja\Console\Constant;

use Valkyrja\Config\Command\ConfigCache;
use Valkyrja\Config\Command\ConfigClear;
use Valkyrja\Console\Command\CacheAll;
use Valkyrja\Console\Command\ClearCache;
use Valkyrja\Console\Command\CommandsList;
use Valkyrja\Console\Command\CommandsListForBash;
use Valkyrja\Console\Command\ConsoleCache;
use Valkyrja\Console\Command\Optimize;
use Valkyrja\Container\Command\ContainerCache;
use Valkyrja\Event\Command\EventsCache;
use Valkyrja\Http\Routing\Command\RoutesCache;
use Valkyrja\Http\Routing\Command\RoutesList;

/**
 * Constant Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider
{
    public const CLEAR_CACHE_COMMAND            = ClearCache::class;
    public const CONFIG_CACHE_COMMAND           = ConfigCache::class;
    public const CONFIG_CLEAR_COMMAND           = ConfigClear::class;
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
