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

namespace Unit\Http\Routing\Attribute\Route\RequestMethod;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Trace;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Trace attribute.
 *
 * @author Melech Mizrachi
 */
class TraceTest extends TestCase
{
    public function testDefaults(): void
    {
        $value = [
            RequestMethod::TRACE,
        ];

        $route = new Trace();

        self::assertSame($value, $route->requestMethods);
    }
}
