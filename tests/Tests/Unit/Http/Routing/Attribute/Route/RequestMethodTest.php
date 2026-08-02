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

use Valkyrja\Http\Message\Enum\RequestMethod as Enum;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RequestMethod attribute.
 */
final class RequestMethodTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = [
            Enum::GET,
            Enum::HEAD,
        ];

        $attribute = new RequestMethod(...$value);

        self::assertSame($value, $attribute->requestMethods);
    }
}
