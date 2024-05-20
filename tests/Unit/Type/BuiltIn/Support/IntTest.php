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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Support;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Integer as Helper;

class IntTest extends TestCase
{
    public function testLessThan(): void
    {
        self::assertTrue(Helper::lessThan(2, 10));
        self::assertFalse(Helper::lessThan(2, 0));
    }

    public function testGreaterThan(): void
    {
        self::assertTrue(Helper::greaterThan(10, 2));
        self::assertFalse(Helper::greaterThan(0, 2));
    }

    public function testDivisible(): void
    {
        self::assertTrue(Helper::divisible(4, 2));
        self::assertFalse(Helper::divisible(4, 3));
    }
}
