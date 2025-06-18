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

use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ResponseStruct attribute.
 *
 * @author Melech Mizrachi
 */
class ResponseStructTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = ResponseStructEnum::class;

        $attribute = new ResponseStruct($value);

        self::assertSame($value, $attribute->name);
    }
}
