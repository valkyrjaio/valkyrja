<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum Port.
 *
 * @author Melech Mizrachi
 */
final class Port extends Enum
{
    public const HTTP  = 80;
    public const HTTPS = 443;
}
