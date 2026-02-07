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

namespace Valkyrja\Tests\Classes\Type\Ulid;

use DateTimeInterface;
use Override;
use Valkyrja\Type\Ulid\Support\Ulid;

/**
 * Test wrapper for Ulid to expose protected methods and allow state manipulation.
 */
class UlidClass extends Ulid
{
    /**
     * Whether to force unpackRandomBytes to return false.
     */
    protected static bool $forceUnpackFail = false;

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
     * Set whether to force unpack to fail.
     */
    public static function setForceUnpackFail(bool $fail): void
    {
        static::$forceUnpackFail = $fail;
    }

    /**
     * Expose getTime for testing.
     */
    public static function testGetTime(DateTimeInterface|null $dateTime = null): string
    {
        return static::getTime($dateTime);
    }

    /**
     * Expose getTimeFromDateTime for testing.
     */
    public static function testGetTimeFromDateTime(DateTimeInterface $dateTime): string
    {
        return static::getTimeFromDateTime($dateTime);
    }

    /**
     * Expose increaseTime for testing.
     */
    public static function testIncreaseTime(string $time): string
    {
        return static::increaseTime($time);
    }

    /**
     * Expose updateRandomBytes for testing.
     */
    public static function testUpdateRandomBytes(): void
    {
        static::updateRandomBytes();
    }

    /**
     * Expose areAllRandomBytesMax for testing.
     */
    public static function testAreAllRandomBytesMax(): bool
    {
        return static::areAllRandomBytesMax();
    }

    /**
     * Expose convertRandomBytesPart for testing.
     */
    public static function testConvertRandomBytesPart(int $index): string
    {
        return static::convertRandomBytesPart($index);
    }

    /**
     * Reset static state for clean testing.
     */
    public static function reset(): void
    {
        static::$time            = '';
        static::$randomBytes     = [];
        static::$forceUnpackFail = false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function unpackRandomBytes(string $bytes): array|false
    {
        if (static::$forceUnpackFail) {
            return false;
        }

        return parent::unpackRandomBytes($bytes);
    }
}
