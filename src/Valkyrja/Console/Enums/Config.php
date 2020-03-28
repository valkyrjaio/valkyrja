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

namespace Valkyrja\Console\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const PROVIDERS = [
        Provider::CONFIG_CACHE_COMMAND,
        Provider::CONFIG_ALL_COMMAND,
        Provider::COMMANDS_LIST_COMMAND,
        Provider::CONSOLE_CACHE_COMMAND,
        Provider::COMMANDS_LIST_FOR_BASH_COMMAND,
        Provider::OPTIMIZE_COMMAND,
        Provider::CONTAINER_CACHE_COMMAND,
        Provider::EVENTS_CACHE_COMMAND,
        Provider::ROUTES_CACHE_COMMAND,
        Provider::ROUTES_LIST_COMMAND,
    ];

    public const DEV_PROVIDERS = [];
}
