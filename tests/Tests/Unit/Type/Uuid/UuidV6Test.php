<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Uuid;

use Exception;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Uuid\Factory\UuidV6Factory;
use Valkyrja\Type\Uuid\UuidV6;

use function json_encode;

final class UuidV6Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $id = new UuidV6();

        self::assertTrue(UuidV6Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = UuidV6::fromValue(UuidV6Factory::generate());

        self::assertTrue(UuidV6Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(TypeInvalidArgumentException::class);

        UuidV6::fromValue(1);
    }

    public function testAsFlatValue(): void
    {
        $id = new UuidV6();

        self::assertTrue(UuidV6Factory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = UuidV6Factory::generate();
        $type     = new UuidV6($value);
        $newValue = UuidV6Factory::generate();

        $modified = $type->modify(static fn (string $subject): string => $newValue);

        self::assertNotSame($type->asValue(), $modified->asValue());
        // Original should be unmodified
        self::assertSame($value, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    /**
     * @throws Exception
     */
    public function testIntJsonSerialize(): void
    {
        $value = UuidV6Factory::generate();
        $type  = new UuidV6($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
