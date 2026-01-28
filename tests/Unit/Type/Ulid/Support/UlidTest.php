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

namespace Valkyrja\Tests\Unit\Type\Ulid\Support;

use DateTime;
use Exception;
use InvalidArgumentException;
use Override;
use Valkyrja\Tests\Classes\Type\Ulid\UlidClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\RuntimeException;
use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;

class UlidTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        UlidClass::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        UlidClass::reset();
        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
    }

    /**
     * @throws Exception
     */
    public function testNoStaticPropertyCrossOver(): void
    {
        // Ensure that a generated Ulid is valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV1 and ensure it is valid
        self::assertTrue(VlidV1::isValid(VlidV1::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV2 and ensure it is valid
        self::assertTrue(VlidV2::isValid(VlidV2::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV3 and ensure it is valid
        self::assertTrue(VlidV3::isValid(VlidV3::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV4 and ensure it is valid
        self::assertTrue(VlidV4::isValid(VlidV4::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
    }

    public function testNotValidException(): void
    {
        $ulid = 'test';

        $this->expectException(InvalidUlidException::class);
        $this->expectExceptionMessage("Invalid ULID $ulid provided.");

        Ulid::validate($ulid);
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
        UlidClass::setTime($futureTime);

        // Set all random bytes to max - this makes areAllRandomBytesMax() return true
        UlidClass::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
            4 => Ulid::MAX_PART,
        ]);

        // Verify the preconditions
        self::assertTrue(UlidClass::testAreAllRandomBytesMax());

        // Generate a ULID - this should trigger the elseif branch (lines 85-87)
        // because the new time from microtime() will be less than the stored future time
        // and all random bytes are at max
        $ulid = UlidClass::generate();

        // The generated ULID should be valid
        self::assertTrue(UlidClass::isValid($ulid));

        // The stored time should now be incremented from what microtime() returned
        // (not from the future time we set, since the time passed to increaseTime is from getTime)
        // The key point is that new random bytes were generated (randomize was called)
        self::assertFalse(UlidClass::testAreAllRandomBytesMax());
    }

    /**
     * Test getTime with negative timestamp throws exception (lines 140-144).
     */
    public function testGetTimeWithNegativeTimestamp(): void
    {
        $dateTime = new DateTime('1960-01-01');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The timestamp must be positive.');

        UlidClass::testGetTime($dateTime);
    }

    /**
     * Test getTimeFromDateTime returns correct format (line 164).
     */
    public function testGetTimeFromDateTime(): void
    {
        $dateTime = new DateTime('2024-01-15 12:30:45.123456');

        $result = UlidClass::testGetTimeFromDateTime($dateTime);

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
        $result = UlidClass::testIncreaseTime($time);

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
        UlidClass::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => Ulid::MAX_PART,
        ]);

        UlidClass::testUpdateRandomBytes();

        $randomBytes = UlidClass::getRandomBytes();

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
        UlidClass::setRandomBytes([
            1 => 100,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
            4 => Ulid::MAX_PART,
        ]);

        UlidClass::testUpdateRandomBytes();

        $randomBytes = UlidClass::getRandomBytes();

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
        UlidClass::setForceUnpackFail(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Random bytes failed to unpack');

        UlidClass::generate();
    }

    /**
     * Test getConvertedRandomBytesForFormat returns correct array (line 322).
     *
     * @throws Exception
     */
    public function testConvertRandomBytesPart(): void
    {
        // Initialize state by generating a ULID
        UlidClass::generate();

        // Test converting each random byte part
        for ($i = 1; $i <= 4; $i++) {
            $result = UlidClass::testConvertRandomBytesPart($i);
            self::assertIsString($result);
        }

        // Test with index > MAX_RANDOM_BYTES returns empty string
        $result = UlidClass::testConvertRandomBytesPart(5);
        self::assertSame('', $result);
    }

    /**
     * Test areAllRandomBytesMax returns correct values.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        UlidClass::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        self::assertFalse(UlidClass::testAreAllRandomBytesMax());

        // Test with all max bytes
        UlidClass::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
            4 => Ulid::MAX_PART,
        ]);

        self::assertTrue(UlidClass::testAreAllRandomBytesMax());
    }

    /**
     * Test generate with a DateTime (covers line 164 via normal flow).
     *
     * @throws Exception
     */
    public function testGenerateWithDateTime(): void
    {
        $dateTime = new DateTime('2024-06-15 10:30:00');

        $ulid = UlidClass::generate($dateTime);

        self::assertTrue(UlidClass::isValid($ulid));
    }
}
