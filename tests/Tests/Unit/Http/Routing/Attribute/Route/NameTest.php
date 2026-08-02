<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Attribute\Route;

use Valkyrja\Http\Routing\Attribute\Route\Name;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Path attribute.
 */
final class NameTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = 'name';

        $attribute = new Name($value);

        self::assertSame($value, $attribute->value);
    }
}
