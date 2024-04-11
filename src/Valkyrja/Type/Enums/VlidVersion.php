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

namespace Valkyrja\Type\Enums;

use Valkyrja\Type\Enum as Contract;
use Valkyrja\Type\Types\Enum;

/**
 * Enum VlidVersion.
 *
 * @author Melech Mizrachi
 */
enum VlidVersion: int implements Contract
{
    use Enum;

    case V1 = 1;
    case V2 = 2;
    case V3 = 3;
    case V4 = 4;
}
