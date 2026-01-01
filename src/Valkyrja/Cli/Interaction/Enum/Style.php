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

/**
 * Enum Style.
 */
enum Style: int
{
    case BOLD       = 1;
    case UNDERSCORE = 4;
    case BLINK      = 5;
    case INVERSE    = 7;
    case CONCEAL    = 8;

    public const int BOLD_DEFAULT       = 22;
    public const int UNDERSCORE_DEFAULT = 24;
    public const int BLINK_DEFAULT      = 25;
    public const int INVERSE_DEFAULT    = 27;
    public const int CONCEAL_DEFAULT    = 28;

    /**
     * @var array<int, int>
     */
    public const array DEFAULTS = [
        1 => self::BOLD_DEFAULT,
        4 => self::UNDERSCORE_DEFAULT,
        5 => self::BLINK_DEFAULT,
        7 => self::INVERSE_DEFAULT,
        8 => self::CONCEAL_DEFAULT,
    ];
}
