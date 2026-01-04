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
use Valkyrja\Type\BuiltIn\Support\MbStr;

class MbStringTest extends TestCase
{
    protected const string VALUE     = 'Some Words';
    protected const string UPPERCASE = 'UPPERCASE';
    protected const string LOWERCASE = 'lowercase';

    public function testSubstr(): void
    {
        self::assertSame('Some', MbStr::substr(self::VALUE, 0, 4));
    }

    public function testContains(): void
    {
        self::assertTrue(MbStr::contains(self::VALUE, 'Some'));
        self::assertFalse(MbStr::contains(self::VALUE, 'nope'));
    }

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', MbStr::toTitleCase(self::VALUE));
        self::assertSame('Uppercase', MbStr::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', MbStr::toTitleCase(self::LOWERCASE));
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', MbStr::toLowerCase(self::VALUE));
        self::assertSame('uppercase', MbStr::toLowerCase('UPPERCASE'));
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', MbStr::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', MbStr::toUpperCase('lowercase'));
    }
}
