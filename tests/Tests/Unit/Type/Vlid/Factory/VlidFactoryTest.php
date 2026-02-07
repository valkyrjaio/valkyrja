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

namespace Valkyrja\Tests\Unit\Type\Vlid\Factory;

use DateTime;
use Exception;
use Override;
use Valkyrja\Tests\Classes\Type\Vlid\VlidFactoryClass;
use Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract\VlidTestCase;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidException;

use function strlen;

final class VlidFactoryTest extends VlidTestCase
{
    #[Override]
    protected function setUp(): void
    {
        VlidFactoryClass::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidFactoryClass::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(Version::V1, VlidFactory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidFactory::isValid($vlid = VlidFactory::generate()));
        self::assertTrue(VlidFactory::isValid($lvlid = VlidFactory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testGenerateToEnsureDefaultsToV1(): void
    {
        self::assertTrue(VlidV1Factory::isValid($vlid = VlidFactory::generate()));
        self::assertTrue(VlidV1Factory::isValid($lvlid = VlidFactory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testV1(): void
    {
        self::assertTrue(VlidV1Factory::isValid($vlid = VlidFactory::v1()));
        self::assertTrue(VlidV1Factory::isValid($lvlid = VlidFactory::v1(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
        self::assertTrue(VlidFactory::isValid($vlid));
        self::assertTrue(VlidFactory::isValid($lvlid));
    }

    /**
     * @throws Exception
     */
    public function testV2(): void
    {
        self::assertTrue(VlidV2Factory::isValid($vlid = VlidFactory::v2()));
        self::assertTrue(VlidV2Factory::isValid($lvlid = VlidFactory::v2(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V2, $vlid);
        $this->ensureVersionInGeneratedString(Version::V2, $lvlid);
        self::assertTrue(VlidFactory::isValid($vlid));
        self::assertTrue(VlidFactory::isValid($lvlid));
    }

    public function testV3(): void
    {
        self::assertTrue(VlidV3Factory::isValid($vlid = VlidFactory::v3()));
        self::assertTrue(VlidV3Factory::isValid($lvlid = VlidFactory::v3(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V3, $vlid);
        $this->ensureVersionInGeneratedString(Version::V3, $lvlid);
        self::assertTrue(VlidFactory::isValid($vlid));
        self::assertTrue(VlidFactory::isValid($lvlid));
    }

    public function testV4(): void
    {
        self::assertTrue(VlidV4Factory::isValid($vlid = VlidFactory::v4()));
        self::assertTrue(VlidV4Factory::isValid($lvlid = VlidFactory::v4(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V4, $vlid);
        $this->ensureVersionInGeneratedString(Version::V4, $lvlid);
        self::assertTrue(VlidFactory::isValid($vlid));
        self::assertTrue(VlidFactory::isValid($lvlid));
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidException::class);
        $this->expectExceptionMessage("Invalid VLID $vlid provided.");

        VlidFactory::validate($vlid);
    }

    /**
     * Test getTimeFromDateTime returns timestamp with microseconds (line 133).
     */
    public function testGetTimeFromDateTime(): void
    {
        $dateTime = new DateTime('2024-01-15 12:30:45.123456');

        $result = VlidFactoryClass::testGetTimeFromDateTime($dateTime);

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
        VlidFactoryClass::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
        ]);

        self::assertFalse(VlidFactoryClass::testAreAllRandomBytesMax());

        // Test with all max bytes (Vlid uses 3 random bytes)
        VlidFactoryClass::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
        ]);

        self::assertTrue(VlidFactoryClass::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID to initialize the state
        VlidFactoryClass::generate();

        $currentTime = VlidFactoryClass::getStoredTime();

        // Set the time to the same value and set all random bytes to max (3 for Vlid)
        VlidFactoryClass::setTime($currentTime);
        VlidFactoryClass::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
        ]);

        // Generate another VLID - this should trigger the elseif branch
        $vlid = VlidFactoryClass::generate();

        // The generated VLID should be valid
        self::assertTrue(VlidFactoryClass::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidFactoryClass::getStoredTime());
    }

    /**
     * Test generate with a DateTime (covers line 133 via normal flow).
     *
     * @throws Exception
     */
    public function testGenerateWithDateTime(): void
    {
        $dateTime = new DateTime('2024-06-15 10:30:00.123456');

        $vlid = VlidFactoryClass::generate($dateTime);

        self::assertTrue(VlidFactoryClass::isValid($vlid));
    }
}
