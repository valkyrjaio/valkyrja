<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Attribute\Route;

use Valkyrja\Cli\Routing\Attribute\Route\Middleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MiddlewareTest extends TestCase
{
    public function testName(): void
    {
        $name       = OutputThrowableCaughtMiddleware::class;
        $middleware = new Middleware(name: $name);

        self::assertSame($name, $middleware->name);
    }
}
