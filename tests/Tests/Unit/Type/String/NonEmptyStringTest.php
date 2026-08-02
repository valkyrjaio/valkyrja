<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\String;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\String\NonEmptyString;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;

final class NonEmptyStringTest extends TestCase
{
    public function testEmptyArrayThrowsException(): void
    {
        $this->expectException(TypeInvalidArgumentException::class);

        new NonEmptyString('');
    }

    public function testConstruct(): void
    {
        $value = 'foo';
        $type  = new NonEmptyString($value);

        self::assertSame($value, $type->asValue());
    }
}
