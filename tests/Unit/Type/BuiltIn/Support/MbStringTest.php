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
use Valkyrja\Type\BuiltIn\Support\MbStr as Helper;

class MbStringTest extends TestCase
{
    protected const VALUE     = 'Some Words';
    protected const UPPERCASE = 'UPPERCASE';
    protected const LOWERCASE = 'lowercase';

    public function testSubstr(): void
    {
        self::assertSame('Some', Helper::substr(self::VALUE, 0, 4));
    }

    public function testContains(): void
    {
        self::assertTrue(Helper::contains(self::VALUE, 'Some'));
        self::assertFalse(Helper::contains(self::VALUE, 'nope'));
    }

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', Helper::toTitleCase(self::VALUE));
        self::assertSame('Uppercase', Helper::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', Helper::toTitleCase(self::LOWERCASE));
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', Helper::toLowerCase(self::VALUE));
        self::assertSame('uppercase', Helper::toLowerCase('UPPERCASE'));
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', Helper::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', Helper::toUpperCase('lowercase'));
    }
}
