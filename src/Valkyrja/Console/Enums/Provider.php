<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Enums;

use Valkyrja\Config\Commands\ConfigCacheCommand;
use Valkyrja\Console\Commands\CacheAllCommand;
use Valkyrja\Console\Commands\CommandsListCommand;
use Valkyrja\Console\Commands\CommandsListForBashCommand;
use Valkyrja\Console\Commands\ConsoleCacheCommand;
use Valkyrja\Console\Commands\OptimizeCommand;
use Valkyrja\Container\Commands\ContainerCacheCommand;
use Valkyrja\Enum\Enum;
use Valkyrja\Event\Commands\EventsCacheCommand;
use Valkyrja\Routing\Commands\RoutesCacheCommand;
use Valkyrja\Routing\Commands\RoutesListCommand;

/**
 * Enum Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider extends Enum
{
    public const CONFIG_CACHE_COMMAND           = ConfigCacheCommand::class;
    public const CONFIG_ALL_COMMAND             = CacheAllCommand::class;
    public const COMMANDS_LIST_COMMAND          = CommandsListCommand::class;
    public const CONSOLE_CACHE_COMMAND          = ConsoleCacheCommand::class;
    public const COMMANDS_LIST_FOR_BASH_COMMAND = CommandsListForBashCommand::class;
    public const OPTIMIZE_COMMAND               = OptimizeCommand::class;
    public const CONTAINER_CACHE_COMMAND        = ContainerCacheCommand::class;
    public const EVENTS_CACHE_COMMAND           = EventsCacheCommand::class;
    public const ROUTES_CACHE_COMMAND           = RoutesCacheCommand::class;
    public const ROUTES_LIST_COMMAND            = RoutesListCommand::class;
}
