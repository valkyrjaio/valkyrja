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

namespace Valkyrja\Support\Enum;

/**
 * Trait Arrayable.
 *
 * @author Melech Mizrachi
 */
trait Arrayable
{
    /**
     * Get enum case names.
     *
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get enum case values.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get enum as an array with name as index and value as value.
     *
     * @return array
     */
    public static function asArray(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get enum as an array with value as index and name as value.
     *
     * @return array
     */
    public static function asReverseArray(): array
    {
        return array_combine(self::values(), self::names());
    }
}
