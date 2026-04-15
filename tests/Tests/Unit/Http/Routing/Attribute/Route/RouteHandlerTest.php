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

namespace Valkyrja\Tests\Unit\Http\Routing\Attribute\Route;

use Valkyrja\Http\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the route handler attribute.
 */
final class RouteHandlerTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = static fn () => null;

        $attribute = new RouteHandler($value);

        self::assertSame($value, $attribute->handler);
    }
}
