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

namespace Unit\Http\Routing\Attribute\Parameter\Regex;

use Valkyrja\Http\Routing\Attribute\Parameter\Regex\AlphaLowercase;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the AlphaLowercase attribute.
 *
 * @author Melech Mizrachi
 */
class AlphaLowercaseTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = Regex::ALPHA_LOWERCASE;

        $attribute = new AlphaLowercase();

        self::assertSame($value, $attribute->value);
    }
}
