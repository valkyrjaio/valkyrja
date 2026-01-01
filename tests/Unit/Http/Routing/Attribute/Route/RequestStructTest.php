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

use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\QueryRequestStructEnum;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the RequestStruct attribute.
 */
class RequestStructTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = QueryRequestStructEnum::class;

        $attribute = new RequestStruct($value);

        self::assertSame($value, $attribute->name);
    }
}
