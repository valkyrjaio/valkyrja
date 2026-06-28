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

namespace Valkyrja\Tests\Classes\Type\Vlid;

use Valkyrja\Type\Vlid\Factory\VlidV3Factory;

/**
 * Test wrapper for VlidV3 to expose protected methods and allow state manipulation.
 */
final class VlidV3Class extends VlidV3Factory
{
    /**
     * Set the time static property.
     */
    public static function setTime(string $time): void
    {
        self::$time = $time;
    }

    /**
     * Get the time static property.
     */
    public static function getStoredTime(): string
    {
        return self::$time;
    }

    /**
     * Set the random bytes static property.
     *
     * @param array<int, int> $randomBytes
     */
    public static function setRandomBytes(array $randomBytes): void
    {
        self::$randomBytes = $randomBytes;
    }

    /**
     * Get the random bytes static property.
     *
     * @return array<int, int>
     */
    public static function getRandomBytes(): array
    {
        return self::$randomBytes;
    }

    /**
     * Expose areAllRandomBytesMax for testing.
     */
    public static function testAreAllRandomBytesMax(): bool
    {
        return self::areAllRandomBytesMax();
    }

    /**
     * Reset static state for clean testing.
     */
    public static function reset(): void
    {
        self::$time        = '';
        self::$randomBytes = [];
    }
}
