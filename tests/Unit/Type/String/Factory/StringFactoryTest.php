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

use Exception;
use JsonException;
use stdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\String\Factory\StringFactory;

class StringFactoryTest extends TestCase
{
    protected const string VALUE = 'Some Words';

    public function testStartsWith(): void
    {
        self::assertTrue(StringFactory::startsWith(self::VALUE, 'Some'));
        self::assertFalse(StringFactory::startsWith(self::VALUE, 'nope'));
    }

    public function testStartsWithAny(): void
    {
        self::assertTrue(StringFactory::startsWithAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(StringFactory::startsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testEndsWith(): void
    {
        self::assertTrue(StringFactory::endsWith(self::VALUE, 'Words'));
        self::assertFalse(StringFactory::endsWith(self::VALUE, 'nope'));
    }

    public function testEndsWithAny(): void
    {
        self::assertTrue(StringFactory::endsWithAny(self::VALUE, 'nope', 'Words'));
        self::assertFalse(StringFactory::endsWithAny(self::VALUE, 'nope', 'no'));
    }

    public function testContains(): void
    {
        self::assertTrue(StringFactory::contains(self::VALUE, 'Some'));
        self::assertFalse(StringFactory::contains(self::VALUE, 'nope'));
    }

    public function testContainsAny(): void
    {
        self::assertTrue(StringFactory::containsAny(self::VALUE, 'nope', 'Some'));
        self::assertFalse(StringFactory::containsAny(self::VALUE, 'nope', 'no'));
    }

    public function testMin(): void
    {
        self::assertTrue(StringFactory::min(self::VALUE, 2));
        self::assertTrue(StringFactory::min(self::VALUE, 10));
        self::assertFalse(StringFactory::min(self::VALUE, 11));
        self::assertFalse(StringFactory::min(self::VALUE, 20));
    }

    public function testMax(): void
    {
        self::assertFalse(StringFactory::max(self::VALUE, 2));
        self::assertFalse(StringFactory::max(self::VALUE, 9));
        self::assertTrue(StringFactory::max(self::VALUE, 10));
        self::assertTrue(StringFactory::max(self::VALUE, 20));
    }

    public function testReplace(): void
    {
        self::assertSame('Stme Wtrds', StringFactory::replace(self::VALUE, 'o', 't'));
    }

    public function testReplaceAll(): void
    {
        self::assertSame('Stmm Wtrds', StringFactory::replaceAll(self::VALUE, ['o', 'e'], ['t', 'm']));
    }

    public function testReplaceAllWith(): void
    {
        self::assertSame('Stmt Wtrds', StringFactory::replaceAllWith(self::VALUE, ['o', 'e'], 't'));
    }

    public function testSubstr(): void
    {
        self::assertSame('Some', StringFactory::substr(self::VALUE, 0, 4));
    }

    /**
     * @throws Exception
     */
    public function testRandom(): void
    {
        self::assertNotSame(StringFactory::random(), StringFactory::random());
    }

    /**
     * @throws Exception
     */
    public function testRandomMd5(): void
    {
        self::assertNotSame(StringFactory::randomMd5(), StringFactory::randomMd5());
    }

    /**
     * @throws Exception
     */
    public function testRandomBase64(): void
    {
        self::assertNotSame(StringFactory::randomBase64(), StringFactory::randomBase64());
    }

    public function testIsEmail(): void
    {
        self::assertTrue(StringFactory::isEmail('test@test.com'));
        self::assertFalse(StringFactory::isEmail('not'));
    }

    public function testIsAlphabetic(): void
    {
        self::assertTrue(StringFactory::isAlphabetic('abc'));
        self::assertFalse(StringFactory::isAlphabetic('abc123'));
    }

    public function testIsLowercase(): void
    {
        self::assertTrue(StringFactory::isLowercase('lowercase'));
        self::assertTrue(StringFactory::isLowercase('lowercase with spaces'));
        self::assertFalse(StringFactory::isLowercase('Capitalized'));
        self::assertFalse(StringFactory::isLowercase('Capitalized with spaces'));
        self::assertFalse(StringFactory::isLowercase('UPPERCASE'));
        self::assertFalse(StringFactory::isLowercase('UPPERCASE WITH SPACES'));
    }

    public function testIsUppercase(): void
    {
        self::assertFalse(StringFactory::isUppercase('lowercase'));
        self::assertFalse(StringFactory::isUppercase('lowercase with spaces'));
        self::assertFalse(StringFactory::isUppercase('Capitalized'));
        self::assertFalse(StringFactory::isUppercase('Capitalized with spaces'));
        self::assertTrue(StringFactory::isUppercase('UPPERCASE'));
        self::assertTrue(StringFactory::isUppercase('UPPERCASE WITH SPACES'));
    }

    /**
     * @throws JsonException
     */
    public function testFromMixed(): void
    {
        $obj      = new stdClass();
        $obj->foo = 'bar';

        $resource = fopen(filename: __DIR__ . '/../../../../storage/.gitignore', mode: 'rb');

        self::assertSame('string', StringFactory::fromMixed('string'));
        self::assertSame('3', StringFactory::fromMixed(3));
        self::assertSame('3.04', StringFactory::fromMixed(3.04));
        self::assertSame('true', StringFactory::fromMixed(true));
        self::assertSame('false', StringFactory::fromMixed(false));
        self::assertSame('null', StringFactory::fromMixed(null));
        self::assertSame('{"foo":"bar"}', StringFactory::fromMixed(['foo' => 'bar']));
        self::assertSame('{"foo":"bar"}', StringFactory::fromMixed($obj));
        self::assertSame('resource', StringFactory::fromMixed($resource));
    }
}
