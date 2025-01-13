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

namespace Valkyrja\Tests\Unit\Http\Message\Constant;

use Valkyrja\Http\Message\Constant\StatusCode;
use Valkyrja\Tests\Unit\TestCase;

class StatusCodeTest extends TestCase
{
    public function testIsValid(): void
    {
        self::assertFalse(StatusCode::isValid(99));
        self::assertTrue(StatusCode::isValid(StatusCode::OK));
        self::assertTrue(StatusCode::isValid(StatusCode::BAD_REQUEST));
        self::assertFalse(StatusCode::isValid(600));
    }
}
