<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Support;

class Exiter
{
    protected static bool $exit = true;

    /**
     * Freeze the exiter.
     */
    public static function freeze(): void
    {
        static::$exit = false;
    }

    /**
     * Unfreeze the exiter.
     */
    public static function unfreeze(): void
    {
        static::$exit = true;
    }

    /**
     * Exit, or don't. Up to you :).
     */
    public static function exit(int $code = 0): void
    {
        static::$exit ? static::exitCallback($code) : static::frozenCallback($code);
    }

    /**
     * Callback for when exiter is frozen.
     */
    public static function frozenCallback(int $code = 0): void
    {
        echo $code;
    }

    /**
     * Callback for when exiter is not frozen.
     *
     * A seam: exit() terminates the process, so no test can call this and go on
     * to assert anything. A Tests\Fixtures subclass overrides it to record the
     * call, which is what covers the unfrozen arm of exit().
     *
     * @codeCoverageIgnore
     */
    protected static function exitCallback(int $code = 0): void
    {
        exit($code);
    }
}
