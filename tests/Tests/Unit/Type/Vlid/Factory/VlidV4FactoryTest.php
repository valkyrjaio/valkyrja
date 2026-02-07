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
use Valkyrja\Tests\Classes\Type\Vlid\VlidV4Class;
use Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract\VlidTestCase;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV4Exception;

class VlidV4FactoryTest extends VlidTestCase
{
    protected const Version VERSION = Version::V4;

    #[Override]
    protected function setUp(): void
    {
        VlidV4Class::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidV4Class::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV4Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV4Factory::isValid($vlid = VlidV4Factory::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV4Factory::isValid($lvlid = VlidV4Factory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV4Exception::class);
        $this->expectExceptionMessage("Invalid VLID V4 $vlid provided.");

        VlidV4Factory::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV4Factory::isValid(VlidV1Factory::generate()));
        self::assertFalse(VlidV4Factory::isValid(VlidV1Factory::generateLowerCase()));
        self::assertFalse(VlidV4Factory::isValid(VlidV2Factory::generate()));
        self::assertFalse(VlidV4Factory::isValid(VlidV2Factory::generateLowerCase()));
        self::assertFalse(VlidV4Factory::isValid(VlidV3Factory::generate()));
        self::assertFalse(VlidV4Factory::isValid(VlidV3Factory::generateLowerCase()));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 54).
     * Note: VlidV4 has only 1 random byte.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max byte
        VlidV4Class::setRandomBytes([
            1 => 100,
        ]);

        self::assertFalse(VlidV4Class::testAreAllRandomBytesMax());

        // Test with max byte (VlidV4 uses 1 random byte)
        VlidV4Class::setRandomBytes([
            1 => UlidFactory::MAX_PART,
        ]);

        self::assertTrue(VlidV4Class::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID V4 to initialize the state
        VlidV4Class::generate();

        $currentTime = VlidV4Class::getStoredTime();

        // Set the time to the same value and set all random bytes to max (1 for VlidV4)
        VlidV4Class::setTime($currentTime);
        VlidV4Class::setRandomBytes([
            1 => UlidFactory::MAX_PART,
        ]);

        // Generate another VLID V4 - this should trigger the elseif branch
        $vlid = VlidV4Class::generate();

        // The generated VLID V4 should be valid
        self::assertTrue(VlidV4Class::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidV4Class::getStoredTime());
    }
}
