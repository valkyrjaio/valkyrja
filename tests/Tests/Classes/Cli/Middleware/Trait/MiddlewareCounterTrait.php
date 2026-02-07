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

namespace Valkyrja\Tests\Classes\Cli\Middleware\Trait;

/**
 * Trait MiddlewareCounter.
 */
trait MiddlewareCounterTrait
{
    protected static int $counter = 0;

    /**
     * Get the counter.
     */
    public static function getCounter(): int
    {
        return static::$counter;
    }

    /**
     * Reset the counter.
     */
    public static function resetCounter(): void
    {
        static::$counter = 0;
    }

    /**
     * Get and reset the counter.
     */
    public static function getAndResetCounter(): int
    {
        $counter = static::getCounter();

        static::resetCounter();

        return $counter;
    }

    /**
     * Update the internal counter.
     */
    protected function updateCounter(): void
    {
        static::$counter++;
    }
}
