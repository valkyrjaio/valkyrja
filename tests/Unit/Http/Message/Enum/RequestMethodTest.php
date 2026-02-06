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

namespace Valkyrja\Tests\Unit\Http\Message\Enum;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function count;

class RequestMethodTest extends TestCase
{
    public function testAllMethod(): void
    {
        $all = RequestMethod::all();

        self::assertCount(count(RequestMethod::cases()), $all);
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
