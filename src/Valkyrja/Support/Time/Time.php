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

namespace Valkyrja\Support\Time;

/**
 * Class Time.
 */
class Time
{
    protected static int|null $frozenTime = null;

    /**
     * Freeze the time.
     */
    public static function freeze(int|null $time = null): void
    {
        static::$frozenTime = $time ?? static::time();
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
