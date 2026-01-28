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

namespace Valkyrja\Tests\Unit\Type\Vlid\Support;

use DateTime;
use Exception;
use Override;
use Valkyrja\Tests\Classes\Type\Vlid\VlidTestWrapper;
use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Support\Vlid;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidException;

use function strlen;

class VlidTest extends AbstractVlidTestCase
{
    #[Override]
    protected function setUp(): void
    {
        VlidTestWrapper::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidTestWrapper::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(Version::V1, Vlid::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(Vlid::isValid($vlid = Vlid::generate()));
        self::assertTrue(Vlid::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testGenerateToEnsureDefaultsToV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::generate()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::v1()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::v1(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    /**
     * @throws Exception
     */
    public function testV2(): void
    {
        self::assertTrue(VlidV2::isValid($vlid = Vlid::v2()));
        self::assertTrue(VlidV2::isValid($lvlid = Vlid::v2(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V2, $vlid);
        $this->ensureVersionInGeneratedString(Version::V2, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV3(): void
    {
        self::assertTrue(VlidV3::isValid($vlid = Vlid::v3()));
        self::assertTrue(VlidV3::isValid($lvlid = Vlid::v3(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V3, $vlid);
        $this->ensureVersionInGeneratedString(Version::V3, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV4(): void
    {
        self::assertTrue(VlidV4::isValid($vlid = Vlid::v4()));
        self::assertTrue(VlidV4::isValid($lvlid = Vlid::v4(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V4, $vlid);
        $this->ensureVersionInGeneratedString(Version::V4, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidException::class);
        $this->expectExceptionMessage("Invalid VLID $vlid provided.");

        Vlid::validate($vlid);
    }

    /**
     * Test getTimeFromDateTime returns timestamp with microseconds (line 133).
     */
    public function testGetTimeFromDateTime(): void
    {
        $dateTime = new DateTime('2024-01-15 12:30:45.123456');

        $result = VlidTestWrapper::testGetTimeFromDateTime($dateTime);

        // Should return Unix timestamp with microseconds (Uu format)
        self::assertIsString($result);
        self::assertMatchesRegularExpression('/^\d+$/', $result);
        // The result should be longer than Ulid's (which uses Uv - milliseconds)
        // Vlid uses Uu which includes microseconds (6 digits instead of 3)
        self::assertGreaterThanOrEqual(16, strlen($result));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 153).
     * Note: Vlid has only 3 random bytes instead of 4 like Ulid.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        VlidTestWrapper::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
        ]);

        self::assertFalse(VlidTestWrapper::testAreAllRandomBytesMax());

        // Test with all max bytes (Vlid uses 3 random bytes)
        VlidTestWrapper::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
        ]);

        self::assertTrue(VlidTestWrapper::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID to initialize the state
        VlidTestWrapper::generate();

        $currentTime = VlidTestWrapper::getStoredTime();

        // Set the time to the same value and set all random bytes to max (3 for Vlid)
        VlidTestWrapper::setTime($currentTime);
        VlidTestWrapper::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
        ]);

        // Generate another VLID - this should trigger the elseif branch
        $vlid = VlidTestWrapper::generate();

        // The generated VLID should be valid
        self::assertTrue(VlidTestWrapper::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidTestWrapper::getStoredTime());
    }

    /**
     * Test generate with a DateTime (covers line 133 via normal flow).
     *
     * @throws Exception
     */
    public function testGenerateWithDateTime(): void
    {
        $dateTime = new DateTime('2024-06-15 10:30:00.123456');

        $vlid = VlidTestWrapper::generate($dateTime);

        self::assertTrue(VlidTestWrapper::isValid($vlid));
    }
}
