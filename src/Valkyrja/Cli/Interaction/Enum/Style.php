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

namespace Valkyrja\Cli\Interaction\Enum;

enum Style: int
{
    case BOLD       = 1;
    case UNDERSCORE = 4;
    case BLINK      = 5;
    case INVERSE    = 7;
    case CONCEAL    = 8;

    /**
     * Get the default style.
     */
    public function getDefault(): int
    {
        return match ($this) {
            self::BOLD       => 22,
            self::UNDERSCORE => 24,
            self::BLINK      => 25,
            self::INVERSE    => 27,
            self::CONCEAL    => 28,
        };
    }
}
