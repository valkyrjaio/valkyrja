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
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Any;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Any attribute.
 *
 * @author Melech Mizrachi
 */
class AnyTest extends TestCase
{
    public function testDefaults(): void
    {
        $value = [
            RequestMethod::CONNECT,
            RequestMethod::DELETE,
            RequestMethod::GET,
            RequestMethod::HEAD,
            RequestMethod::OPTIONS,
            RequestMethod::PATCH,
            RequestMethod::POST,
            RequestMethod::PUT,
            RequestMethod::TRACE,
        ];

        $route = new Any();

        self::assertSame($value, $route->requestMethods);
    }
}
