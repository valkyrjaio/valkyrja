<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Ulid;

use DateTimeInterface;
use Override;
use Valkyrja\Type\Ulid\Factory\UlidFactory;

/**
 * Test wrapper for Ulid to expose protected methods and allow state manipulation.
 */
final class UlidFactoryFixture extends UlidFactory
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
     * Set whether to force unpack to fail.
     */
    public static function setForceUnpackFail(bool $fail): void
    {
        self::$forceUnpackFail = $fail;
    }

    /**
     * Expose getTime for testing.
     */
    public static function testGetTime(DateTimeInterface|null $dateTime = null): string
    {
        return self::getTime($dateTime);
    }

    /**
     * Expose getTimeFromDateTime for testing.
     */
    public static function testGetTimeFromDateTime(DateTimeInterface $dateTime): string
    {
        return self::getTimeFromDateTime($dateTime);
    }

    /**
     * Expose increaseTime for testing.
     */
    public static function testIncreaseTime(string $time): string
    {
        return self::increaseTime($time);
    }

    /**
     * Expose updateRandomBytes for testing.
     */
    public static function testUpdateRandomBytes(): void
    {
        self::updateRandomBytes();
    }

    /**
     * Expose areAllRandomBytesMax for testing.
     */
    public static function testAreAllRandomBytesMax(): bool
    {
        return self::areAllRandomBytesMax();
    }

    /**
     * Expose convertRandomBytesPart for testing.
     */
    public static function testConvertRandomBytesPart(int $index): string
    {
        return self::convertRandomBytesPart($index);
    }

    /**
     * Reset static state for clean testing.
     */
    public static function reset(): void
    {
        self::$time            = '';
        self::$randomBytes     = [];
        self::$forceUnpackFail = false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function unpackRandomBytes(string $bytes): array|false
    {
        if (self::$forceUnpackFail) {
            return false;
        }

        return parent::unpackRandomBytes($bytes);
    }
}
