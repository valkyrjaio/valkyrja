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
 *
 * @author Melech Mizrachi
 */
enum Style: int
{
    case BOLD       = 1;
    case UNDERSCORE = 4;
    case BLINK      = 5;
    case INVERSE    = 7;
    case CONCEAL    = 8;

    case BOLD_DEFAULT       = 22;
    case UNDERSCORE_DEFAULT = 24;
    case BLINK_DEFAULT      = 25;
    case INVERSE_DEFAULT    = 27;
    case CONCEAL_DEFAULT    = 28;
}
