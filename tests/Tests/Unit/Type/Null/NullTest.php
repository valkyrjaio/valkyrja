<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Null;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Null\NullT;

use function json_encode;

final class NullTest extends TestCase
{
    protected const null VALUE = null;

    public function testValue(): void
    {
        $type = new NullT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = NullT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new NullT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new NullT(self::VALUE);
        // The new value
        $newValue = 'anything';

        $modified = $type->modify(static fn (mixed $subject): string => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be unmodified and always null
        self::assertNotSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new NullT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
