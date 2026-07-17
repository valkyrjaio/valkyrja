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

use Exception;
use Override;
use Valkyrja\Tests\Fixtures\Type\Vlid\VlidV3Fixture;
use Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract\VlidTestCase;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV3Exception;

final class VlidV3FactoryTest extends VlidTestCase
{
    protected const Version VERSION = Version::V3;

    #[Override]
    protected function setUp(): void
    {
        VlidV3Fixture::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidV3Fixture::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV3Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV3Factory::isValid($vlid = VlidV3Factory::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV3Factory::isValid($lvlid = VlidV3Factory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV3Exception::class);
        $this->expectExceptionMessage("Invalid VLID V3 $vlid provided.");

        VlidV3Factory::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV3Factory::isValid(VlidV1Factory::generate()));
        self::assertFalse(VlidV3Factory::isValid(VlidV1Factory::generateLowerCase()));
        self::assertFalse(VlidV3Factory::isValid(VlidV2Factory::generate()));
        self::assertFalse(VlidV3Factory::isValid(VlidV2Factory::generateLowerCase()));
        self::assertFalse(VlidV3Factory::isValid(VlidV4Factory::generate()));
        self::assertFalse(VlidV3Factory::isValid(VlidV4Factory::generateLowerCase()));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 54).
     * Note: VlidV3 has only 2 random bytes.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        VlidV3Fixture::setRandomBytes([
            1 => 100,
            2 => 200,
        ]);

        self::assertFalse(VlidV3Fixture::testAreAllRandomBytesMax());

        // Test with all max bytes (VlidV3 uses 2 random bytes)
        VlidV3Fixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
        ]);

        self::assertTrue(VlidV3Fixture::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID V3 to initialize the state
        VlidV3Fixture::generate();

        $currentTime = VlidV3Fixture::getStoredTime();

        // Set the time to the same value and set all random bytes to max (2 for VlidV3)
        VlidV3Fixture::setTime($currentTime);
        VlidV3Fixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
        ]);

        // Generate another VLID V3 - this should trigger the elseif branch
        $vlid = VlidV3Fixture::generate();

        // The generated VLID V3 should be valid
        self::assertTrue(VlidV3Fixture::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidV3Fixture::getStoredTime());
    }
}
