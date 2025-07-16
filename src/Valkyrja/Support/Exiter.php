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

namespace Valkyrja\Support;

/**
 * Class Exiter.
 *
 * @author Melech Mizrachi
 */
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
        static::$exit ? exit($code) : '';
    }
}
