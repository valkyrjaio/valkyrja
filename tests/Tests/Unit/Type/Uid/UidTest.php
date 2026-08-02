<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Uid;

use Exception;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Uid\Factory\UidFactory;
use Valkyrja\Type\Uid\Throwable\Exception\InvalidUidException;
use Valkyrja\Type\Uid\Uid;

use function json_encode;

final class UidTest extends TestCase
{
    public function testConstruct(): void
    {
        $id = new Uid('abc123');

        self::assertTrue(UidFactory::isValid($id->asValue()));
    }

    public function testFromValue(): void
    {
        $id = Uid::fromValue('abc123');

        self::assertTrue(UidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(TypeInvalidArgumentException::class);

        Uid::fromValue(1);
    }

    public function testAsFlatValue(): void
    {
        $id = new Uid('abc123');

        self::assertTrue(UidFactory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = 'abc123';
        $type     = new Uid($value);
        $newValue = 'def456';

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
        $value = 'abc123';
        $type  = new Uid($value);

        self::assertSame(json_encode($value), json_encode($type));
    }

    public function testValidateWithInvalidValue(): void
    {
        $this->expectException(InvalidUidException::class);

        new Uid('@#*(&');
    }
}
