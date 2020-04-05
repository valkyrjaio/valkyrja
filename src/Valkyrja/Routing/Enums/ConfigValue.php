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

namespace Valkyrja\Routing\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Http\Exceptions\HttpException;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const MIDDLEWARE                  = [];
    public const MIDDLEWARE_GROUPS           = [];
    public const CONTROLLERS                 = [];
    public const USE_TRAILING_SLASH          = false;
    public const USE_ABSOLUTE_URLS           = false;
    public const USE_ANNOTATIONS             = false;
    public const USE_ANNOTATIONS_EXCLUSIVELY = false;
    public const FILE_PATH                   = '';
    public const CACHE_FILE_PATH             = '';
    public const USE_CACHE_FILE              = false;
    public const HTTP_EXCEPTION              = HttpException::class;

    public static array $defaults = [
        CKP::MIDDLEWARE                  => self::MIDDLEWARE,
        CKP::MIDDLEWARE_GROUPS           => self::MIDDLEWARE_GROUPS,
        CKP::CONTROLLERS                 => self::CONTROLLERS,
        CKP::HTTP_EXCEPTION              => self::HTTP_EXCEPTION,
        CKP::USE_TRAILING_SLASH          => self::USE_TRAILING_SLASH,
        CKP::USE_ABSOLUTE_URLS           => self::USE_ABSOLUTE_URLS,
        CKP::USE_ANNOTATIONS             => self::USE_ANNOTATIONS,
        CKP::USE_ANNOTATIONS_EXCLUSIVELY => self::USE_ANNOTATIONS_EXCLUSIVELY,
        CKP::FILE_PATH                   => self::FILE_PATH,
        CKP::CACHE_FILE_PATH             => self::CACHE_FILE_PATH,
        CKP::USE_CACHE                   => self::USE_CACHE_FILE,
    ];
}
