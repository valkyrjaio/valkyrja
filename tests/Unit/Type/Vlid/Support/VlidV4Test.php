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
use Valkyrja\Tests\Classes\Type\Vlid\VlidV4TestWrapper;
use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV4Exception;

class VlidV4Test extends AbstractVlidTestCase
{
    protected const Version VERSION = Version::V4;

    #[Override]
    protected function setUp(): void
    {
        VlidV4TestWrapper::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidV4TestWrapper::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV4::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV4::isValid($vlid = VlidV4::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV4::isValid($lvlid = VlidV4::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV4Exception::class);
        $this->expectExceptionMessage("Invalid VLID V4 $vlid provided.");

        VlidV4::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV4::isValid(VlidV1::generate()));
        self::assertFalse(VlidV4::isValid(VlidV1::generateLowerCase()));
        self::assertFalse(VlidV4::isValid(VlidV2::generate()));
        self::assertFalse(VlidV4::isValid(VlidV2::generateLowerCase()));
        self::assertFalse(VlidV4::isValid(VlidV3::generate()));
        self::assertFalse(VlidV4::isValid(VlidV3::generateLowerCase()));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 54).
     * Note: VlidV4 has only 1 random byte.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max byte
        VlidV4TestWrapper::setRandomBytes([
            1 => 100,
        ]);

        self::assertFalse(VlidV4TestWrapper::testAreAllRandomBytesMax());

        // Test with max byte (VlidV4 uses 1 random byte)
        VlidV4TestWrapper::setRandomBytes([
            1 => Ulid::MAX_PART,
        ]);

        self::assertTrue(VlidV4TestWrapper::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID V4 to initialize the state
        VlidV4TestWrapper::generate();

        $currentTime = VlidV4TestWrapper::getStoredTime();

        // Set the time to the same value and set all random bytes to max (1 for VlidV4)
        VlidV4TestWrapper::setTime($currentTime);
        VlidV4TestWrapper::setRandomBytes([
            1 => Ulid::MAX_PART,
        ]);

        // Generate another VLID V4 - this should trigger the elseif branch
        $vlid = VlidV4TestWrapper::generate();

        // The generated VLID V4 should be valid
        self::assertTrue(VlidV4TestWrapper::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidV4TestWrapper::getStoredTime());
    }
}
