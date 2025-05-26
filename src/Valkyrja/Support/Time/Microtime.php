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
 * Class Microtime.
 *
 * @author Melech Mizrachi
 */
class Microtime
{
    protected static float|null $frozenTime = null;

    public static function freeze(float|null $microtime = null): void
    {
        static::$frozenTime = $microtime ?? static::microtime();
    }

    public static function unfreeze(): void
    {
        static::$frozenTime = null;
    }

    public static function get(): float
    {
        return static::$frozenTime ?? static::microtime();
    }

    protected static function microtime(): float
    {
        return microtime(true);
    }
}
