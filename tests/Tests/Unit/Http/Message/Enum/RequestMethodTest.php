<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Enum;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function count;

final class RequestMethodTest extends TestCase
{
    public function testAllMethod(): void
    {
        $all = RequestMethod::all();

        self::assertCount(9, $all);
        self::assertCount(count(RequestMethod::cases()) - 1, $all);
        self::assertSame([
            RequestMethod::GET,
            RequestMethod::HEAD,
            RequestMethod::POST,
            RequestMethod::PUT,
            RequestMethod::DELETE,
            RequestMethod::CONNECT,
            RequestMethod::OPTIONS,
            RequestMethod::TRACE,
            RequestMethod::PATCH,
        ], $all);
    }
}
