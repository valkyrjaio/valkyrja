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

namespace Valkyrja\Tests\Unit\Type\String\Factory;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\String\Factory\MbStringFactory;

final class MbStringFactoryTest extends TestCase
{
    protected const string VALUE     = 'Some Words';
    protected const string UPPERCASE = 'UPPERCASE';
    protected const string LOWERCASE = 'lowercase';

    public function testSubstr(): void
    {
        self::assertSame('Some', MbStringFactory::substr(self::VALUE, 0, 4));
    }

    public function testContains(): void
    {
        self::assertTrue(MbStringFactory::contains(self::VALUE, 'Some'));
        self::assertFalse(MbStringFactory::contains(self::VALUE, 'nope'));
    }

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', MbStringFactory::toTitleCase(self::VALUE));
        self::assertSame('Uppercase', MbStringFactory::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', MbStringFactory::toTitleCase(self::LOWERCASE));
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', MbStringFactory::toLowerCase(self::VALUE));
        self::assertSame('uppercase', MbStringFactory::toLowerCase('UPPERCASE'));
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', MbStringFactory::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', MbStringFactory::toUpperCase('lowercase'));
    }
}
