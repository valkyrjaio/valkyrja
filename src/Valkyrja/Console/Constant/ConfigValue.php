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

use Valkyrja\Console\Commander\Commander;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    /** @var array<array-key, class-string<Commander>> */
    public const array PROVIDERS = [
        Provider::CLEAR_CACHE_COMMAND,
        Provider::COMMANDS_LIST_COMMAND,
        Provider::COMMANDS_LIST_FOR_BASH_COMMAND,
        Provider::OPTIMIZE_CACHE_COMMAND,
        Provider::ROUTES_LIST_COMMAND,
    ];
    /** @var array<array-key, class-string<Commander>> */
    public const array DEV_PROVIDERS = [];
}
