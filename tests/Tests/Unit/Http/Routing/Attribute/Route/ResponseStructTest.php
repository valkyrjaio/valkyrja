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

use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Tests\Fixtures\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ResponseStruct attribute.
 */
final class ResponseStructTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = ResponseStructEnum::first;

        $attribute = new ResponseStruct($value);

        self::assertSame($value, $attribute->struct);
    }
}
