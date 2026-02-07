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

use Exception;
use Override;
use Valkyrja\Tests\Classes\Type\Vlid\VlidV2Class;
use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV2Exception;

class VlidV2Test extends AbstractVlidTestCase
{
    protected const Version VERSION = Version::V2;

    #[Override]
    protected function setUp(): void
    {
        VlidV2Class::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidV2Class::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV2::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV2::isValid($vlid = VlidV2::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV2::isValid($lvlid = VlidV2::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV2Exception::class);
        $this->expectExceptionMessage("Invalid VLID V2 $vlid provided.");

        VlidV2::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV2::isValid(VlidV1::generate()));
        self::assertFalse(VlidV2::isValid(VlidV1::generateLowerCase()));
        self::assertFalse(VlidV2::isValid(VlidV3::generate()));
        self::assertFalse(VlidV2::isValid(VlidV3::generateLowerCase()));
        self::assertFalse(VlidV2::isValid(VlidV4::generate()));
        self::assertFalse(VlidV2::isValid(VlidV4::generateLowerCase()));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 53).
     * Note: VlidV2 has 4 random bytes like Ulid.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        VlidV2Class::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        self::assertFalse(VlidV2Class::testAreAllRandomBytesMax());

        // Test with all max bytes (VlidV2 uses 4 random bytes)
        VlidV2Class::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
            4 => Ulid::MAX_PART,
        ]);

        self::assertTrue(VlidV2Class::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID V2 to initialize the state
        VlidV2Class::generate();

        $currentTime = VlidV2Class::getStoredTime();

        // Set the time to the same value and set all random bytes to max (4 for VlidV2)
        VlidV2Class::setTime($currentTime);
        VlidV2Class::setRandomBytes([
            1 => Ulid::MAX_PART,
            2 => Ulid::MAX_PART,
            3 => Ulid::MAX_PART,
            4 => Ulid::MAX_PART,
        ]);

        // Generate another VLID V2 - this should trigger the elseif branch
        $vlid = VlidV2Class::generate();

        // The generated VLID V2 should be valid
        self::assertTrue(VlidV2Class::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidV2Class::getStoredTime());
    }
}
