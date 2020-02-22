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

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum Alias.
 *
 * @author Melech Mizrachi
 */
final class Alias extends Enum
{
    public const REQUEST_METHOD = 'RequestMethod';
    public const STATUS_CODE    = 'StatusCode';
}
