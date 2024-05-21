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

namespace Valkyrja\Console\Constants;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const HANDLERS        = [];
    public const PROVIDERS       = [
        Provider::CLEAR_CACHE_COMMAND,
        Provider::CONFIG_CACHE_COMMAND,
        Provider::CONFIG_CLEAR_COMMAND,
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
    public const DEV_PROVIDERS   = [];
    public const QUIET           = false;
    public const USE_ANNOTATIONS = false;
    public const FILE_PATH       = '';
    public const CACHE_FILE_PATH = '';
    public const USE_CACHE_FILE  = false;

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::HANDLERS        => self::HANDLERS,
        CKP::PROVIDERS       => self::PROVIDERS,
        CKP::DEV_PROVIDERS   => self::DEV_PROVIDERS,
        CKP::QUIET           => self::QUIET,
        CKP::USE_ANNOTATIONS => self::USE_ANNOTATIONS,
        CKP::FILE_PATH       => self::FILE_PATH,
        CKP::CACHE_FILE_PATH => self::CACHE_FILE_PATH,
        CKP::USE_CACHE       => self::USE_CACHE_FILE,
    ];
}
