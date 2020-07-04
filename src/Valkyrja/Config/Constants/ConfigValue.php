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

namespace Valkyrja\Config\Constants;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const PROVIDERS       = [];
    public const CACHE_FILE_PATH = '';
    public const USER_CACHE      = false;

    public static array $defaults = [
        'providers'     => self::PROVIDERS,
        'cacheFilePath' => self::CACHE_FILE_PATH,
        'useCache'      => self::USER_CACHE,
    ];
}
