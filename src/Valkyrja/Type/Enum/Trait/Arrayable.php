<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
