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

use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Tests\Fixtures\Http\Struct\QueryRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RequestStruct attribute.
 */
final class RequestStructTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = QueryRequestStructEnum::first;

        $attribute = new RequestStruct($value);

        self::assertSame($value, $attribute->struct);
    }
}
