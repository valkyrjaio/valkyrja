<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Float;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Float\FloatT;
use Valkyrja\Type\Float\Throwable\Exception\FloatInvalidFromValueException;

use function json_encode;

final class FloatTest extends TestCase
{
    protected const float VALUE = 1.25;

    public function testValue(): void
    {
        $type = new FloatT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = FloatT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testFromEmptyArrayValue(): void
    {
        self::assertSame(0.0, FloatT::fromValue([])->asValue());
    }

    public function testFromNonEmptyArrayValue(): void
    {
        self::assertSame(1.0, FloatT::fromValue(['x'])->asValue());
    }

    public function testFromUnsupportedValueThrows(): void
    {
        $this->expectException(FloatInvalidFromValueException::class);

        FloatT::fromValue(null);
    }

    public function testAsFlatValue(): void
    {
        $type = new FloatT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new FloatT(self::VALUE);
        // The new value
        $newValue = 2.46;

        $modified = $type->modify(static fn (float $subject): float => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new FloatT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
