<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Array;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Array\NonEmptyArray;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;

final class NonEmptyArrayTest extends TestCase
{
    public function testEmptyArrayThrowsException(): void
    {
        $this->expectException(TypeInvalidArgumentException::class);

        new NonEmptyArray([]);
    }

    public function testConstruct(): void
    {
        $value = ['test' => 'foo'];
        $type  = new NonEmptyArray($value);

        self::assertSame($value, $type->asValue());
    }
}
