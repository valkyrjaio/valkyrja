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

namespace Unit\Http\Routing\Attribute\Route;

use Valkyrja\Http\Message\Enum\RequestMethod as Enum;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the RequestMethod attribute.
 *
 * @author Melech Mizrachi
 */
class RequestMethodTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = [
            Enum::GET,
            Enum::HEAD,
        ];

        $attribute = new RequestMethod(...$value);

        self::assertSame($value, $attribute->methods);
    }
}
