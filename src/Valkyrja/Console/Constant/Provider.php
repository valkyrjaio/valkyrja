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

use Valkyrja\Console\Command\ClearCache;
use Valkyrja\Console\Command\CommandsList;
use Valkyrja\Console\Command\CommandsListForBash;
use Valkyrja\Console\Command\OptimizeCacheCommand;
use Valkyrja\Http\Routing\Command\RoutesList;

/**
 * Constant Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider
{
    public const string CLEAR_CACHE_COMMAND            = ClearCache::class;
    public const string COMMANDS_LIST_COMMAND          = CommandsList::class;
    public const string COMMANDS_LIST_FOR_BASH_COMMAND = CommandsListForBash::class;
    public const string OPTIMIZE_CACHE_COMMAND         = OptimizeCacheCommand::class;
    public const string ROUTES_LIST_COMMAND            = RoutesList::class;
}
