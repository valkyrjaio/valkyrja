<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Path\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
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
}
