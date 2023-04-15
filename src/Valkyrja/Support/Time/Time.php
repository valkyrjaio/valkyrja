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
 *
 * @author Melech Mizrachi
 */
class Time
{
    protected static int|null $frozenTime = null;

    public static function freeze(int $time = null): void
    {
        static::$frozenTime = $time ?? static::time();
    }

    public static function unfreeze(): void
    {
        static::$frozenTime = null;
    }

    public static function get(): int
    {
        return static::$frozenTime ?? static::time();
    }

    protected static function time(): int
    {
        return time();
    }
}
