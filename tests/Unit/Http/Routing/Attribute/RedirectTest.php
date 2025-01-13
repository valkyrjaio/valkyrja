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

namespace Unit\Http\Routing\Attribute;

use Valkyrja\Http\Routing\Attribute\Redirect;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Redirect attribute.
 *
 * @author Melech Mizrachi
 */
class RedirectTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = '/path';

        $attribute = new Redirect($value);

        self::assertSame($value, $attribute->to);
    }
}
