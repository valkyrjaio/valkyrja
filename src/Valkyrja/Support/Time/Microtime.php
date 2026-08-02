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

use function microtime;

class Microtime
{
    protected static float|null $frozenTime = null;

    /**
     * Freeze the microtime.
     */
    public static function freeze(float $microtime): void
    {
        static::$frozenTime = $microtime;
    }

    /**
     * Unfreeze the microtime.
     */
    public static function unfreeze(): void
    {
        static::$frozenTime = null;
    }

    /**
     * Get the frozen, or unfrozen microtime.
     */
    public static function get(): float
    {
        return static::$frozenTime ?? static::microtime();
    }

    /**
     * Get the microtime.
     */
    protected static function microtime(): float
    {
        return microtime(true);
    }
}
