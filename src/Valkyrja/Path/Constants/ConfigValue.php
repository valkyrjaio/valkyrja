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

namespace Valkyrja\Path\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const PATTERNS = [
        PathPattern::NUM                  => '(\d+)',
        PathPattern::ID                   => '(\d+)',
        PathPattern::SLUG                 => '([a-zA-Z0-9-]+)',
        PathPattern::UUID                 => '([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})',
        PathPattern::ALPHA                => '([a-zA-Z]+)',
        PathPattern::ALPHA_LOWERCASE      => '([a-z]+)',
        PathPattern::ALPHA_UPPERCASE      => '([A-Z]+)',
        PathPattern::ALPHA_NUM            => '([a-zA-Z0-9]+)',
        PathPattern::ALPHA_NUM_UNDERSCORE => '(\w+)',
    ];

    public static array $defaults = [
        CKP::PATTERNS => self::PATTERNS,
    ];
}
