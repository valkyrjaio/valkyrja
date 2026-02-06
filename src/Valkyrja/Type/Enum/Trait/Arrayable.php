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

namespace Valkyrja\Type\Enum\Trait;

use Valkyrja\Type\Enum\Support\Enumerable;

trait Arrayable
{
    /**
     * Get enum case names.
     *
     * @return string[]
     */
    public static function names(): array
    {
        return Enumerable::names(static::class);
    }

    /**
     * Get enum case values.
     *
     * @return string[]|int[]
     */
    public static function values(): array
    {
        return Enumerable::values(static::class);
    }

    /**
     * Get enum as an array with name as index and value as value.
     *
     * @return array<string, int|string>
     */
    public static function asArray(): array
    {
        return Enumerable::asArray(static::class);
    }

    /**
     * Get enum as an array with value as index and name as value.
     *
     * @return array<int|string, string>
     */
    public static function asReverseArray(): array
    {
        return Enumerable::asReverseArray(static::class);
    }
}
