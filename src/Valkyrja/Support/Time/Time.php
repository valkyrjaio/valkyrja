<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Support\Time;

use function time;

class Time
{
    protected static int|null $frozenTime = null;

    /**
     * Freeze the time.
     */
    public static function freeze(int $time): void
    {
        static::$frozenTime = $time;
    }

    /**
     * Unfreeze the time.
     */
    public static function unfreeze(): void
    {
        static::$frozenTime = null;
    }

    /**
     * Get the frozen, or unfrozen time.
     */
    public static function get(): int
    {
        return static::$frozenTime ?? static::time();
    }

    /**
     * Get the time.
     */
    protected static function time(): int
    {
        return time();
    }
}
