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

namespace Valkyrja\Type\Uuid\Enum;

/**
 * Enum Version.
 *
 * @author Melech Mizrachi
 */
enum Version: int
{
    case V1 = 1;
    case V3 = 3;
    case V4 = 4;
    case V5 = 5;
    case V6 = 6;
    case V7 = 7;
    case V8 = 8;
}
