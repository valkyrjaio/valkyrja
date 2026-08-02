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
            default          => 28,
        };
    }
}
