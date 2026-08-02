<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Bool;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Bool\BoolT;

use function json_encode;

final class BoolTest extends TestCase
{
    protected const bool VALUE = true;

    public function testValue(): void
    {
        $type = new BoolT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = BoolT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new BoolT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new BoolT(self::VALUE);
        // The new value
        $newValue = false;

        $modified = $type->modify(static fn (bool $subject): bool => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new BoolT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
