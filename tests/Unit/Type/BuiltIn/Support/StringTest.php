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

use Exception;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Str as Helper;

class StringTest extends TestCase
{
    protected const VALUE = 'Some Words';

    public function testStartsWith(): void
    {
        self::assertTrue(Helper::startsWith(self::VALUE, 'Some'));
        self::assertFalse(Helper::startsWith(self::VALUE, 'nope'));
    }

    public function testStartsWithAny(): void
    {
        self::assertTrue(Helper::startsWithAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(Helper::startsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testEndsWith(): void
    {
        self::assertTrue(Helper::endsWith(self::VALUE, 'Words'));
        self::assertFalse(Helper::endsWith(self::VALUE, 'nope'));
    }

    public function testEndsWithAny(): void
    {
        self::assertTrue(Helper::endsWithAny(self::VALUE, 'nope', 'Words'));
        self::assertFalse(Helper::endsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testContains(): void
    {
        self::assertTrue(Helper::contains(self::VALUE, 'Some'));
        self::assertFalse(Helper::contains(self::VALUE, 'nope'));
    }

    public function testContainsAny(): void
    {
        self::assertTrue(Helper::containsAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(Helper::containsAny(self::VALUE, 'nope', 'no'));
    }

    public function testMin(): void
    {
        self::assertTrue(Helper::min(self::VALUE, 2));
        self::assertTrue(Helper::min(self::VALUE, 10));
        self::assertFalse(Helper::min(self::VALUE, 11));
        self::assertFalse(Helper::min(self::VALUE, 20));
    }

    public function testMax(): void
    {
        self::assertFalse(Helper::max(self::VALUE, 2));
        self::assertFalse(Helper::max(self::VALUE, 9));
        self::assertTrue(Helper::max(self::VALUE, 10));
        self::assertTrue(Helper::max(self::VALUE, 20));
    }

    public function testReplace(): void
    {
        self::assertSame('Stme Wtrds', Helper::replace(self::VALUE, 'o', 't'));
    }

    public function testReplaceAll(): void
    {
        self::assertSame('Stmm Wtrds', Helper::replaceAll(self::VALUE, ['o', 'e'], ['t', 'm']));
    }

    public function testReplaceAllWith(): void
    {
        self::assertSame('Stmt Wtrds', Helper::replaceAllWith(self::VALUE, ['o', 'e'], 't'));
    }

    public function testSubstr(): void
    {
        self::assertSame('Some', Helper::substr(self::VALUE, 0, 4));
    }

    /**
     * @throws Exception
     */
    public function testRandom(): void
    {
        self::assertNotSame(Helper::random(), Helper::random());
    }

    /**
     * @throws Exception
     */
    public function testRandomMd5(): void
    {
        self::assertNotSame(Helper::randomMd5(), Helper::randomMd5());
    }

    /**
     * @throws Exception
     */
    public function testRandomBase64(): void
    {
        self::assertNotSame(Helper::randomBase64(), Helper::randomBase64());
    }

    public function testIsEmail(): void
    {
        self::assertTrue(Helper::isEmail('test@test.com'));
        self::assertFalse(Helper::isEmail('not'));
    }

    public function testIsAlphabetic(): void
    {
        self::assertTrue(Helper::isAlphabetic('abc'));
        self::assertFalse(Helper::isAlphabetic('abc123'));
    }

    public function testIsLowercase(): void
    {
        self::assertTrue(Helper::isLowercase('lowercase'));
        self::assertFalse(Helper::isLowercase('Capitalized'));
        self::assertFalse(Helper::isLowercase('UPPERCASE'));
    }

    public function testIsUppercase(): void
    {
        self::assertFalse(Helper::isUppercase('lowercase'));
        self::assertFalse(Helper::isUppercase('Capitalized'));
        self::assertTrue(Helper::isUppercase('UPPERCASE'));
    }
}
