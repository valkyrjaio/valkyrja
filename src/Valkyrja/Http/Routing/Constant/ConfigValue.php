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

namespace Valkyrja\Http\Routing\Constant;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const MIDDLEWARE         = [];
    public const MIDDLEWARE_GROUPS  = [];
    public const CONTROLLERS        = [];
    public const USE_TRAILING_SLASH = false;
    public const USE_ABSOLUTE_URLS  = false;
    public const USE_ANNOTATIONS    = false;
    public const FILE_PATH          = '';
    public const CACHE_FILE_PATH    = '';
    public const USE_CACHE_FILE     = false;

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::MIDDLEWARE         => self::MIDDLEWARE,
        CKP::MIDDLEWARE_GROUPS  => self::MIDDLEWARE_GROUPS,
        CKP::CONTROLLERS        => self::CONTROLLERS,
        CKP::USE_TRAILING_SLASH => self::USE_TRAILING_SLASH,
        CKP::USE_ABSOLUTE_URLS  => self::USE_ABSOLUTE_URLS,
        CKP::USE_ANNOTATIONS    => self::USE_ANNOTATIONS,
        CKP::FILE_PATH          => self::FILE_PATH,
        CKP::CACHE_FILE_PATH    => self::CACHE_FILE_PATH,
        CKP::USE_CACHE          => self::USE_CACHE_FILE,
    ];
}
