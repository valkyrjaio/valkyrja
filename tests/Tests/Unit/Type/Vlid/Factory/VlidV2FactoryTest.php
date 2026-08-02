<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Vlid\Factory;

use Exception;
use Override;
use Valkyrja\Tests\Fixtures\Type\Vlid\VlidV2Fixture;
use Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract\VlidTestCase;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV2Exception;

final class VlidV2FactoryTest extends VlidTestCase
{
    protected const Version VERSION = Version::V2;

    #[Override]
    protected function setUp(): void
    {
        VlidV2Fixture::reset();
    }

    #[Override]
    protected function tearDown(): void
    {
        VlidV2Fixture::reset();
        parent::tearDown();
    }

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV2Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV2Factory::isValid($vlid = VlidV2Factory::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV2Factory::isValid($lvlid = VlidV2Factory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV2Exception::class);
        $this->expectExceptionMessage("Invalid VLID V2 $vlid provided.");

        VlidV2Factory::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV2Factory::isValid(VlidV1Factory::generate()));
        self::assertFalse(VlidV2Factory::isValid(VlidV1Factory::generateLowerCase()));
        self::assertFalse(VlidV2Factory::isValid(VlidV3Factory::generate()));
        self::assertFalse(VlidV2Factory::isValid(VlidV3Factory::generateLowerCase()));
        self::assertFalse(VlidV2Factory::isValid(VlidV4Factory::generate()));
        self::assertFalse(VlidV2Factory::isValid(VlidV4Factory::generateLowerCase()));
    }

    /**
     * Test areAllRandomBytesMax returns correct values (line 53).
     * Note: VlidV2 has 4 random bytes like Ulid.
     */
    public function testAreAllRandomBytesMax(): void
    {
        // Test with non-max bytes
        VlidV2Fixture::setRandomBytes([
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
        ]);

        self::assertFalse(VlidV2Fixture::testAreAllRandomBytesMax());

        // Test with all max bytes (VlidV2 uses 4 random bytes)
        VlidV2Fixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
            4 => UlidFactory::MAX_PART,
        ]);

        self::assertTrue(VlidV2Fixture::testAreAllRandomBytesMax());
    }

    /**
     * Test that generate handles when all random bytes are at max.
     *
     * @throws Exception
     */
    public function testGenerateWithAllRandomBytesAtMax(): void
    {
        // First generate a VLID V2 to initialize the state
        VlidV2Fixture::generate();

        $currentTime = VlidV2Fixture::getStoredTime();

        // Set the time to the same value and set all random bytes to max (4 for VlidV2)
        VlidV2Fixture::setTime($currentTime);
        VlidV2Fixture::setRandomBytes([
            1 => UlidFactory::MAX_PART,
            2 => UlidFactory::MAX_PART,
            3 => UlidFactory::MAX_PART,
            4 => UlidFactory::MAX_PART,
        ]);

        // Generate another VLID V2 - this should trigger the elseif branch
        $vlid = VlidV2Fixture::generate();

        // The generated VLID V2 should be valid
        self::assertTrue(VlidV2Fixture::isValid($vlid));

        // The time should have been incremented
        self::assertGreaterThan($currentTime, VlidV2Fixture::getStoredTime());
    }
}
