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
use Valkyrja\Type\Uuid\Factory\UuidV8Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV8Exception;

final class UuidV8FactoryTest extends UuidTestCase
{
    protected const Version VERSION = Version::V8;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV8Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV8Factory::isValid(UuidFactory::v1()));
        self::assertFalse(UuidV8Factory::isValid(UuidFactory::v3(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV8Factory::isValid(UuidFactory::v4()));
        self::assertFalse(UuidV8Factory::isValid(UuidFactory::v5(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV8Factory::isValid(UuidFactory::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV8Exception::class);
        $this->expectExceptionMessage("Invalid UUID V8 $uuid provided.");

        UuidV8Factory::validate($uuid);
    }

    /**
     * @throws Exception
     */
    public function testGenerateProducesValidV8Uuid(): void
    {
        $uuid = UuidV8Factory::generate();

        self::assertTrue(UuidV8Factory::isValid($uuid));
    }
}
