<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Uuid\Factory;

use Exception;
use Valkyrja\Tests\Unit\Type\Uuid\Factory\Abstract\UuidTestCase;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV7Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV7Exception;

final class UuidV7FactoryTest extends UuidTestCase
{
    protected const Version VERSION = Version::V7;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV7Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV7Factory::isValid(UuidFactory::v1()));
        self::assertFalse(UuidV7Factory::isValid(UuidFactory::v3(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV7Factory::isValid(UuidFactory::v4()));
        self::assertFalse(UuidV7Factory::isValid(UuidFactory::v5(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV7Factory::isValid(UuidFactory::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV7Exception::class);
        $this->expectExceptionMessage("Invalid UUID V7 $uuid provided.");

        UuidV7Factory::validate($uuid);
    }

    /**
     * @throws Exception
     */
    public function testGenerateProducesValidV7Uuid(): void
    {
        $uuid = UuidV7Factory::generate();

        self::assertTrue(UuidV7Factory::isValid($uuid));
    }
}
