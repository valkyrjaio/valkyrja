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

use DateTimeInterface;
use Valkyrja\Type\Vlid\Factory\VlidFactory;

/**
 * Test wrapper for Vlid to expose protected methods and allow state manipulation.
 */
class VlidFactoryClass extends VlidFactory
{
    /**
     * Set the time static property.
     */
    public static function setTime(string $time): void
    {
        static::$time = $time;
    }

    /**
     * Get the time static property.
     */
    public static function getStoredTime(): string
    {
        return static::$time;
    }

    /**
     * Set the random bytes static property.
     *
     * @param array<int, int> $randomBytes
     */
    public static function setRandomBytes(array $randomBytes): void
    {
        static::$randomBytes = $randomBytes;
    }

    /**
     * Get the random bytes static property.
     *
     * @return array<int, int>
     */
    public static function getRandomBytes(): array
    {
        return static::$randomBytes;
    }

    /**
     * Expose getTimeFromDateTime for testing.
     */
    public static function testGetTimeFromDateTime(DateTimeInterface $dateTime): string
    {
        return static::getTimeFromDateTime($dateTime);
    }

    /**
     * Expose areAllRandomBytesMax for testing.
     */
    public static function testAreAllRandomBytesMax(): bool
    {
        return static::areAllRandomBytesMax();
    }

    /**
     * Reset static state for clean testing.
     */
    public static function reset(): void
    {
        static::$time        = '';
        static::$randomBytes = [];
    }
}
