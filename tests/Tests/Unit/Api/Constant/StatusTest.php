<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Api\Constant;

use ReflectionClass;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Status constants.
 */
final class StatusTest extends TestCase
{
    public function testSuccessConstant(): void
    {
        self::assertSame('success', Status::SUCCESS);
    }

    public function testErrorConstant(): void
    {
        self::assertSame('error', Status::ERROR);
    }

    public function testFailConstant(): void
    {
        self::assertSame('fail', Status::FAIL);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new ReflectionClass(Status::class);

        self::assertTrue($reflection->isFinal());
    }

    public function testAllConstantsAreStrings(): void
    {
        $reflection = new ReflectionClass(Status::class);
        $constants  = $reflection->getConstants();

        foreach ($constants as $name => $value) {
            self::assertIsString($value, "Constant {$name} should be a string");
        }
    }
}
