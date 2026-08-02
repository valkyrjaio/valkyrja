<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Ulid\Factory;

use DateTime;
use Exception;
use InvalidArgumentException;
use Override;
use Valkyrja\Tests\Fixtures\Type\Ulid\UlidFactoryFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Ulid\Throwable\Exception\UlidRandomBytesFailureException;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;

final class UlidFactoryTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        UlidFactoryFixture::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        UlidFactoryFixture::reset();
        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
    }

    /**
     * @throws Exception
     */
    public function testNoStaticPropertyCrossOver(): void
    {
        // Ensure that a generated Ulid is valid
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
        // Generate a VlidV1 and ensure it is valid
        self::assertTrue(VlidV1Factory::isValid(VlidV1Factory::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
        // Generate a VlidV2 and ensure it is valid
        self::assertTrue(VlidV2Factory::isValid(VlidV2Factory::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
        // Generate a VlidV3 and ensure it is valid
        self::assertTrue(VlidV3Factory::isValid(VlidV3Factory::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
        // Generate a VlidV4 and ensure it is valid
        self::assertTrue(VlidV4Factory::isValid(VlidV4Factory::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(UlidFactory::isValid(UlidFactory::generate()));
        self::assertTrue(UlidFactory::isValid(UlidFactory::generateLowerCase()));
    }

    public function testNotValidException(): void
    {
        $ulid = 'test';

        $this->expectException(InvalidUlidException::class);
        $this->expectExceptionMessage("Invalid ULID $ulid provided.");

        UlidFactory::validate($ulid);
    }

    /**
     * Test that generate handles when all random bytes are at max (lines 85-87).
     * This test ensures we hit the elseif branch when:
     * 1. doesTimeMatch() returns false (new time <= stored time)
     * 2. areAllRandomBytesMax() returns true.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // Set the stored time to a far future value so that microtime() will return
        // a smaller value, making doesTimeMatch() return false
        $futureTime = '9999999999999';
        UlidFactoryFixture::setTime($futureTime);

        // Set all random bytes to max - this makes areAllRandomBytesMax() return true
        UlidFactoryFixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
            4 => UlidFactory::MAX_PART,
        ]);

        // Verify the preconditions
        self::assertTrue(UlidFactoryFixture::testAreAllRandomBytesMax());

        // Generate a ULID - this should trigger the elseif branch (lines 85-87)
        // because the new time from microtime() will be less than the stored future time
        // and all random bytes are at max
        $ulid = UlidFactoryFixture::generate();

        // The generated ULID should be valid
        self::assertTrue(UlidFactoryFixture::isValid($ulid));

        // The stored time should now be incremented from what microtime() returned
        // (not from the future time we set, since the time passed to increaseTime is from getTime)
        // The key point is that new random bytes were generated (randomize was called)
        self::assertFalse(UlidFactoryFixture::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate hits the else branch (lines 92-95) when the time does not
     * advance past the stored time and the random bytes are not all at max:
     * 1. doesTimeMatch() returns false (microtime() <= stored future time, no DateTime)
     * 2. areAllRandomBytesMax() returns false.
     *
     * @throws Exception
     */
    public function testGenerateWhenTimeDoesNotAdvanceAndBytesNotMax(): void
    {
        // Stored time far in the future so microtime() is smaller -> doesTimeMatch() false.
        UlidFactoryFixture::setTime('9999999999999');

        // Random bytes not at max -> areAllRandomBytesMax() false -> else branch.
        UlidFactoryFixture::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        self::assertFalse(UlidFactoryFixture::testAreAllRandomBytesMax());

        $ulid = UlidFactoryFixture::generate();

        self::assertTrue(UlidFactoryFixture::isValid($ulid));
    }

    /**
     * Test doesTimeMatch() when a date time is passed in and the time it produces is
     * exactly the previously stored time. Neither `$time > static::$time` nor
     * `$time !== static::$time` holds, so the whole condition is false even though a
     * date time was given — the remaining branch of the `$dateTime !== null && ...`
     * half of the condition.
     *
     * @throws Exception
     */
    public function testGenerateWithDateTimeMatchingStoredTime(): void
    {
        $dateTime = new DateTime('2024-01-15 12:30:45.123456');

        // Store exactly the time this date time produces.
        UlidFactoryFixture::setTime($dateTime->format('Uv'));

        // Random bytes not at max -> areAllRandomBytesMax() false -> else branch.
        UlidFactoryFixture::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        $ulid = UlidFactoryFixture::generate($dateTime);

        self::assertTrue(UlidFactoryFixture::isValid($ulid));
    }

    /**
     * Test getTime with negative timestamp throws exception (lines 140-144).
     */
    public function testGetTimeWithNegativeTimestamp(): void
    {
        $dateTime = new DateTime('1960-01-01');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The timestamp must be positive.');

        UlidFactoryFixture::testGetTime($dateTime);
    }

    /**
     * Test getTimeFromDateTime returns correct format (line 164).
     */
    public function testGetTimeFromDateTime(): void
    {
        $dateTime = new DateTime('2024-01-15 12:30:45.123456');

        $result = UlidFactoryFixture::testGetTimeFromDateTime($dateTime);

        // Should return Unix timestamp in milliseconds (Uv format)
        self::assertIsString($result);
        self::assertMatchesRegularExpression('/^\d+$/', $result);
    }

    /**
     * Test increaseTime increments the time string (line 195).
     */
    public function testIncreaseTime(): void
    {
        $time   = '1705312800000';
        $result = UlidFactoryFixture::testIncreaseTime($time);

        self::assertSame('1705312800001', $result);
    }

    /**
     * Test updateRandomBytes resets bytes at max to 0 (line 206).
     *
     * @throws Exception
     */
    public function testUpdateRandomBytesResetsMaxToZero(): void
    {
        // Set random bytes where the last one is at max
        UlidFactoryFixture::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => UlidFactory::MAX_PART,
        ]);

        UlidFactoryFixture::testUpdateRandomBytes();

        $randomBytes = UlidFactoryFixture::getRandomBytes();

        // The 4th byte should be reset to 0 and 3rd byte incremented
        self::assertSame(0, $randomBytes[4]);
        self::assertSame(301, $randomBytes[3]);
    }

    /**
     * Test updateRandomBytes with multiple bytes at max.
     *
     * @throws Exception
     */
    public function testUpdateRandomBytesWithMultipleBytesAtMax(): void
    {
        // Set random bytes where multiple are at max
        UlidFactoryFixture::setRandomBytes([
            1 => 100,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
            4 => UlidFactory::MAX_PART,
        ]);

        UlidFactoryFixture::testUpdateRandomBytes();

        $randomBytes = UlidFactoryFixture::getRandomBytes();

        // Bytes 2, 3, 4 should be reset to 0, and byte 1 should be incremented
        self::assertSame(101, $randomBytes[1]);
        self::assertSame(0, $randomBytes[2]);
        self::assertSame(0, $randomBytes[3]);
        self::assertSame(0, $randomBytes[4]);
    }

    /**
     * Test that generateRandomBytes throws RuntimeException when unpack fails (line 246).
     *
     * @throws Exception
     */
    public function testGenerateRandomBytesThrowsOnUnpackFailure(): void
    {
        UlidFactoryFixture::setForceUnpackFail(true);

        $this->expectException(UlidRandomBytesFailureException::class);
        $this->expectExceptionMessage('Random bytes failed to unpack');

        UlidFactoryFixture::generate();
    }

    /**
     * Test getConvertedRandomBytesForFormat returns correct array (line 322).
     *
     * @throws Exception
     */
    public function testConvertRandomBytesPart(): void
    {
        // Initialize state by generating a ULID
        UlidFactoryFixture::generate();

        // Test converting each random byte part
        for ($i = 1; $i <= 4; $i++) {
            $result = UlidFactoryFixture::testConvertRandomBytesPart($i);
            self::assertIsString($result);
        }

        // Test with index > MAX_RANDOM_BYTES returns empty string
        $result = UlidFactoryFixture::testConvertRandomBytesPart(5);
        self::assertSame('', $result);
    }

    /**
     * Test areAllRandomBytesMax returns correct values.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        UlidFactoryFixture::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        self::assertFalse(UlidFactoryFixture::testAreAllRandomBytesMax());

        // Test with all max bytes
        UlidFactoryFixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
            4 => UlidFactory::MAX_PART,
        ]);

        self::assertTrue(UlidFactoryFixture::testAreAllRandomBytesMax());
    }

    /**
     * Test generate with a DateTime (covers line 164 via normal flow).
     *
     * @throws Exception
     */
    public function testGenerateWithDateTime(): void
    {
        $dateTime = new DateTime('2024-06-15 10:30:00');

        $ulid = UlidFactoryFixture::generate($dateTime);

        self::assertTrue(UlidFactoryFixture::isValid($ulid));
    }
}
