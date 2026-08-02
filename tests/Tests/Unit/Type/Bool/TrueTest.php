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

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Bool\TrueT;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class TrueTest extends TestCase
{
    protected const true VALUE = true;

    public function testValue(): void
    {
        $type = new TrueT();

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = TrueT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new TrueT();

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new TrueT();
        // The new value
        $newValue = false;

        $modified = $type->modify(static fn (bool $subject): bool => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be unmodified and always true
        self::assertNotSame($newValue, $modified->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $type = new TrueT();

        self::assertSame(json_encode(self::VALUE, JSON_THROW_ON_ERROR), json_encode($type, JSON_THROW_ON_ERROR));
    }
}
