<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum WhereType.
 */
final class WhereType extends Enum
{
    public const EQUALS = '=';
    public const LIKE   = 'LIKE';

    protected const VALUES = [
        self::EQUALS,
        self::LIKE,
    ];
}
