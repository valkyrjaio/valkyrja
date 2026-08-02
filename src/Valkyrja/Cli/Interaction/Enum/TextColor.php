<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Enum;

enum TextColor: int
{
    case BLACK         = 30;
    case RED           = 31;
    case GREEN         = 32;
    case YELLOW        = 33;
    case BLUE          = 34;
    case MAGENTA       = 35;
    case CYAN          = 36;
    case WHITE         = 37;
    case DARK_GRAY     = 90;
    case LIGHT_RED     = 91;
    case LIGHT_GREEN   = 92;
    case LIGHT_YELLOW  = 93;
    case LIGHT_BLUE    = 94;
    case LIGHT_MAGENTA = 95;
    case LIGHT_CYAN    = 96;
    case LIGHT_WHITE   = 97;

    /**
     * Get the default text color.
     */
    public function getDefault(): int
    {
        return 39;
    }
}
