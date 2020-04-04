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

namespace Valkyrja\Path\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const PATTERNS = [
        'num'                  => '(\d+)',
        'slug'                 => '([a-zA-Z0-9-]+)',
        'alpha'                => '([a-zA-Z]+)',
        'alpha-lowercase'      => '([a-z]+)',
        'alpha-uppercase'      => '([A-Z]+)',
        'alpha-num'            => '([a-zA-Z0-9]+)',
        'alpha-num-underscore' => '(\w+)',
    ];

    public static array $defaults = [
        CKP::PATTERNS => self::PATTERNS,
    ];
}
