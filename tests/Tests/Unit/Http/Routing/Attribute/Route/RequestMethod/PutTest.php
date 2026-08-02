<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Attribute\Route\RequestMethod;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Put;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Put attribute.
 */
final class PutTest extends TestCase
{
    public function testDefaults(): void
    {
        $value = [
            RequestMethod::PUT,
        ];

        $route = new Put();

        self::assertSame($value, $route->requestMethods);
    }
}
