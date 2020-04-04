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

namespace Valkyrja\Event\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const LISTENERS                   = [];
    public const USE_ANNOTATIONS             = false;
    public const USE_ANNOTATIONS_EXCLUSIVELY = false;
    public const FILE_PATH                   = '';
    public const CACHE_FILE_PATH             = '';
    public const USE_CACHE_FILE              = false;

    public static array $defaults = [
        CKP::LISTENERS                   => self::LISTENERS,
        CKP::USE_ANNOTATIONS             => self::USE_ANNOTATIONS,
        CKP::USE_ANNOTATIONS_EXCLUSIVELY => self::USE_ANNOTATIONS_EXCLUSIVELY,
        CKP::FILE_PATH                   => self::FILE_PATH,
        CKP::CACHE_FILE_PATH             => self::CACHE_FILE_PATH,
        CKP::USE_CACHE                   => self::USE_CACHE_FILE,
    ];
}
