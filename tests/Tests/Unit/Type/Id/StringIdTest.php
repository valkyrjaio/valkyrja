<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Id;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Id\StringId;
use Valkyrja\Type\Id\Throwable\Exception\IdInvalidFromValueException;

use function json_encode;

final class StringIdTest extends TestCase
{
    protected const string VALUE       = 'foo';
    protected const int    INT_VALUE   = 1;
    protected const float  FLOAT_VALUE = 1.1;

    public function testValue(): void
    {
        $type = new StringId(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = StringId::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testFromIntValue(): void
    {
        $typeFromValue = StringId::fromValue(self::INT_VALUE);

        self::assertSame('1', $typeFromValue->asValue());
    }

    public function testFromFloatValue(): void
    {
        $typeFromValue = StringId::fromValue(self::FLOAT_VALUE);

        self::assertSame('1.1', $typeFromValue->asValue());
    }

    public function testUnsupportedFromValueThrows(): void
    {
        $this->expectException(IdInvalidFromValueException::class);

        StringId::fromValue(true);
    }

    public function testAsFlatValue(): void
    {
        $type = new StringId(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new StringId(self::VALUE);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static fn (string $subject): string => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new StringId(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
