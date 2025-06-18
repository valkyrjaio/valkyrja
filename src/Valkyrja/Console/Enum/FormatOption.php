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

namespace Valkyrja\Console\Enum;

/**
 * Enum FormatOption.
 *
 * @author Melech Mizrachi
 */
enum FormatOption: int
{
    case BOLD       = 1;
    case UNDERSCORE = 4;
    case BLINK      = 5;
    case INVERSE    = 7;
    case CONCEAL    = 8;

    /** @var array<int, int> */
    public const array DEFAULT = [
        1 => 22,
        4 => 24,
        5 => 25,
        7 => 27,
        8 => 28,
    ];
}
