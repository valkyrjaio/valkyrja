<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Enum\Contract;

interface ArrayableContract
{
    /**
     * Get the object's named properties as an array.
     *
     * @return string[]
     */
    public static function names(): array;

    /**
     * Get the object's values as an array.
     *
     * @return string[]|int[]
     */
    public static function values(): array;

    /**
     * Get the object as an array.
     *
     * @return array<string, int|string>
     */
    public static function asArray(): array;

    /**
     * Get the object as a reversed array.
     *
     * @return array<int|string, string>
     */
    public static function asReverseArray(): array;
}
