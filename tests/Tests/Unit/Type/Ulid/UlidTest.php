<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Ulid;

use Exception;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Ulid\Ulid;

use function json_encode;

final class UlidTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $id = new Ulid();

        self::assertTrue(UlidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = Ulid::fromValue(UlidFactory::generate());

        self::assertTrue(UlidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(TypeInvalidArgumentException::class);

        Ulid::fromValue(1);
    }

    public function testAsFlatValue(): void
    {
        $id = new Ulid();

        self::assertTrue(UlidFactory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = UlidFactory::generate();
        $type     = new Ulid($value);
        $newValue = UlidFactory::generate();

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
        $value = UlidFactory::generate();
        $type  = new Ulid($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
