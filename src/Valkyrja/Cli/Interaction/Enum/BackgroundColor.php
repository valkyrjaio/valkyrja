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

enum BackgroundColor: int
{
    case BLACK         = 40;
    case RED           = 41;
    case GREEN         = 42;
    case YELLOW        = 43;
    case BLUE          = 44;
    case MAGENTA       = 45;
    case CYAN          = 46;
    case WHITE         = 47;
    case DARK_GRAY     = 100;
    case LIGHT_RED     = 101;
    case LIGHT_GREEN   = 102;
    case LIGHT_YELLOW  = 103;
    case LIGHT_BLUE    = 104;
    case LIGHT_MAGENTA = 105;
    case LIGHT_CYAN    = 106;
    case LIGHT_WHITE   = 107;

    /**
     * Get the default background color.
     */
    public function getDefault(): int
    {
        return 49;
    }
}
