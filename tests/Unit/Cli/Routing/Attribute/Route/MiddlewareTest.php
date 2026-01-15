<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Attribute\Route;

use Valkyrja\Cli\Routing\Attribute\Route\Middleware;
use Valkyrja\Cli\Server\Middleware\OutputThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MiddlewareTest extends TestCase
{
    public function testName(): void
    {
        $name       = OutputThrowableCaughtMiddleware::class;
        $middleware = new Middleware(name: $name);

        self::assertSame($name, $middleware->name);
    }
}
