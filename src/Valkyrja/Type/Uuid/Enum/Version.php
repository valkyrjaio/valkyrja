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

use Valkyrja\Type\BuiltIn\Enum\Contract\Enum as Contract;
use Valkyrja\Type\BuiltIn\Enum\Enum;

/**
 * Enum Version.
 *
 * @author Melech Mizrachi
 */
enum Version: int implements Contract
{
    use Enum;

    case V1 = 1;
    case V3 = 3;
    case V4 = 4;
    case V5 = 5;
    case V6 = 6;
    case V7 = 7;
    case V8 = 8;
}
