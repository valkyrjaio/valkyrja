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
 * Enum SameSite.
 *
 * @author Melech Mizrachi
 */
final class SameSite extends Enum
{
    public const LAX    = 'lax';
    public const STRICT = 'strict';
}