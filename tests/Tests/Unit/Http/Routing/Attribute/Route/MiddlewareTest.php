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

use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Tests\Fixtures\Http\Middleware\RequestReceivedMiddlewareFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Middleware attribute.
 */
final class MiddlewareTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = RequestReceivedMiddlewareFixture::class;

        $attribute = new Middleware($value);

        self::assertSame($value, $attribute->name);
    }
}
