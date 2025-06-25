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

namespace Valkyrja\Cli\Routing\Enum;

/**
 * Enum OptionValueMode.
 *
 * @author Melech Mizrachi
 */
enum OptionValueMode
{
    case NONE;
    case DEFAULT;
    case ARRAY;
}
