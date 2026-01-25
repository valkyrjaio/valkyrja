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
use JsonException;
use stdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\BuiltIn\Support\Str;

class StringTest extends TestCase
{
    protected const string VALUE = 'Some Words';

    public function testStartsWith(): void
    {
        self::assertTrue(Str::startsWith(self::VALUE, 'Some'));
        self::assertFalse(Str::startsWith(self::VALUE, 'nope'));
    }

    public function testStartsWithAny(): void
    {
        self::assertTrue(Str::startsWithAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(Str::startsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testEndsWith(): void
    {
        self::assertTrue(Str::endsWith(self::VALUE, 'Words'));
        self::assertFalse(Str::endsWith(self::VALUE, 'nope'));
    }

    public function testEndsWithAny(): void
    {
        self::assertTrue(Str::endsWithAny(self::VALUE, 'nope', 'Words'));
        self::assertFalse(Str::endsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testContains(): void
    {
        self::assertTrue(Str::contains(self::VALUE, 'Some'));
        self::assertFalse(Str::contains(self::VALUE, 'nope'));
    }

    public function testContainsAny(): void
    {
        self::assertTrue(Str::containsAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(Str::containsAny(self::VALUE, 'nope', 'no'));
    }

    public function testMin(): void
    {
        self::assertTrue(Str::min(self::VALUE, 2));
        self::assertTrue(Str::min(self::VALUE, 10));
        self::assertFalse(Str::min(self::VALUE, 11));
        self::assertFalse(Str::min(self::VALUE, 20));
    }

    public function testMax(): void
    {
        self::assertFalse(Str::max(self::VALUE, 2));
        self::assertFalse(Str::max(self::VALUE, 9));
        self::assertTrue(Str::max(self::VALUE, 10));
        self::assertTrue(Str::max(self::VALUE, 20));
    }

    public function testReplace(): void
    {
        self::assertSame('Stme Wtrds', Str::replace(self::VALUE, 'o', 't'));
    }

    public function testReplaceAll(): void
    {
        self::assertSame('Stmm Wtrds', Str::replaceAll(self::VALUE, ['o', 'e'], ['t', 'm']));
    }

    public function testReplaceAllWith(): void
    {
        self::assertSame('Stmt Wtrds', Str::replaceAllWith(self::VALUE, ['o', 'e'], 't'));
    }

    public function testSubstr(): void
    {
        self::assertSame('Some', Str::substr(self::VALUE, 0, 4));
    }

    /**
     * @throws Exception
     */
    public function testRandom(): void
    {
        self::assertNotSame(Str::random(), Str::random());
    }

    /**
     * @throws Exception
     */
    public function testRandomMd5(): void
    {
        self::assertNotSame(Str::randomMd5(), Str::randomMd5());
    }

    /**
     * @throws Exception
     */
    public function testRandomBase64(): void
    {
        self::assertNotSame(Str::randomBase64(), Str::randomBase64());
    }

    public function testIsEmail(): void
    {
        self::assertTrue(Str::isEmail('test@test.com'));
        self::assertFalse(Str::isEmail('not'));
    }

    public function testIsAlphabetic(): void
    {
        self::assertTrue(Str::isAlphabetic('abc'));
        self::assertFalse(Str::isAlphabetic('abc123'));
    }

    public function testIsLowercase(): void
    {
        self::assertTrue(Str::isLowercase('lowercase'));
        self::assertTrue(Str::isLowercase('lowercase with spaces'));
        self::assertFalse(Str::isLowercase('Capitalized'));
        self::assertFalse(Str::isLowercase('Capitalized with spaces'));
        self::assertFalse(Str::isLowercase('UPPERCASE'));
        self::assertFalse(Str::isLowercase('UPPERCASE WITH SPACES'));
    }

    public function testIsUppercase(): void
    {
        self::assertFalse(Str::isUppercase('lowercase'));
        self::assertFalse(Str::isUppercase('lowercase with spaces'));
        self::assertFalse(Str::isUppercase('Capitalized'));
        self::assertFalse(Str::isUppercase('Capitalized with spaces'));
        self::assertTrue(Str::isUppercase('UPPERCASE'));
        self::assertTrue(Str::isUppercase('UPPERCASE WITH SPACES'));
    }

    /**
     * @throws JsonException
     */
    public function testFromMixed(): void
    {
        $obj      = new stdClass();
        $obj->foo = 'bar';

        $resource = fopen(filename: __DIR__ . '/../../../../storage/.gitignore', mode: 'rb');

        self::assertSame('string', Str::fromMixed('string'));
        self::assertSame('3', Str::fromMixed(3));
        self::assertSame('3.04', Str::fromMixed(3.04));
        self::assertSame('true', Str::fromMixed(true));
        self::assertSame('false', Str::fromMixed(false));
        self::assertSame('null', Str::fromMixed(null));
        self::assertSame('{"foo":"bar"}', Str::fromMixed(['foo' => 'bar']));
        self::assertSame('{"foo":"bar"}', Str::fromMixed($obj));
        self::assertSame('resource', Str::fromMixed($resource));
    }
}
