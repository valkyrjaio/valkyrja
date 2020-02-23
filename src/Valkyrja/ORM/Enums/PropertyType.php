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

namespace Valkyrja\ORM\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum PropertyType.
 */
final class PropertyType extends Enum
{
    public const ARRAY  = 'array';
    public const OBJECT = 'object';

    protected static ?array $VALUES = [
        self::ARRAY,
        self::OBJECT,
    ];
}
