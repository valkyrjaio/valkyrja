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

namespace Unit\Http\Routing\Attribute\Parameter;

use Valkyrja\Http\Routing\Attribute\Parameter\Regex;
use Valkyrja\Http\Routing\Constant\Regex as RegexConstant;
use Valkyrja\Http\Routing\Exception\InvalidParameterRegexException;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Regex attribute.
 *
 * @author Melech Mizrachi
 */
class RegexTest extends TestCase
{
    public function testAttribute(): void
    {
        $value = RegexConstant::ANY;

        $attribute = new Regex($value);

        self::assertSame($value, $attribute->value);
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidParameterRegexException::class);

        $value = '/';

        new Regex($value);
    }
}
