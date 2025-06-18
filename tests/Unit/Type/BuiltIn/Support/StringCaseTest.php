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
use Valkyrja\Type\BuiltIn\Support\StrCase as Helper;

class StringCaseTest extends TestCase
{
    protected const string VALUE     = 'Some Words';
    protected const string UPPERCASE = 'UPPERCASE';
    protected const string LOWERCASE = 'lowercase';

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', Helper::toTitleCase(self::VALUE));
        self::assertSame('UPPERCASE', Helper::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', Helper::toTitleCase(self::LOWERCASE));
    }

    public function testAllToTitleCase(): void
    {
        self::assertSame(
            ['Some Words', 'UPPERCASE', 'Lowercase'],
            Helper::allToTitleCase(self::VALUE, self::UPPERCASE, self::LOWERCASE)
        );
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', Helper::toLowerCase(self::VALUE));
        self::assertSame('uppercase', Helper::toLowerCase('UPPERCASE'));
    }

    public function testAllToLowerCase(): void
    {
        self::assertSame(
            ['some words', 'uppercase'],
            Helper::allToLowerCase(self::VALUE, 'UPPERCASE')
        );
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', Helper::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', Helper::toUpperCase('lowercase'));
    }

    public function testAllToUpperCase(): void
    {
        self::assertSame(
            ['SOME WORDS', 'LOWERCASE'],
            Helper::allToUpperCase(self::VALUE, 'lowercase')
        );
    }

    public function testToCapitalized(): void
    {
        self::assertSame('Some Words', Helper::toCapitalized('some words'));
        self::assertSame('Lowercase', Helper::toCapitalized('lowercase'));
        self::assertSame('Lowercase-Fire', Helper::toCapitalized('lowercase-fire', '-'));
    }

    public function testAllToCapitalized(): void
    {
        self::assertSame(
            ['Some Words', 'Lowercase'],
            Helper::allToCapitalized('some words', 'lowercase')
        );
    }

    public function testToCapitalizedWords(): void
    {
        self::assertSame('Some Words', Helper::toCapitalizedWords('some words'));
        self::assertSame('StudlyCased', Helper::toCapitalizedWords('StudlyCased'));
        self::assertSame('CamelCase', Helper::toCapitalizedWords('camelCase'));
        self::assertSame('Slug Cased', Helper::toCapitalizedWords('slug-cased'));
        self::assertSame('Snake Cased', Helper::toCapitalizedWords('snake_cased'));
    }

    public function testAllToCapitalizedWords(): void
    {
        self::assertSame(
            ['Some Words', 'StudlyCased', 'CamelCase', 'Slug Cased', 'Snake Cased'],
            Helper::allToCapitalizedWords('some words', 'StudlyCased', 'camelCase', 'slug-cased', 'snake_cased')
        );
    }

    public function testToSnakeCase(): void
    {
        self::assertSame('some_words', Helper::toSnakeCase(self::VALUE));
        self::assertSame('studly_cased', Helper::toSnakeCase('StudlyCased'));
        self::assertSame('slug_cased', Helper::toSnakeCase('slug-cased'));
    }

    public function testAllToSnakeCase(): void
    {
        self::assertSame(
            ['some_words', 'studly_cased', 'slug_cased'],
            Helper::allToSnakeCase(self::VALUE, 'StudlyCased', 'slug-cased')
        );
    }

    public function testToSlug(): void
    {
        self::assertSame('some-words', Helper::toSlug(self::VALUE));
        self::assertSame('studly-cased', Helper::toSlug('StudlyCased'));
        self::assertSame('snake-cased', Helper::toSlug('snake_cased'));
        self::assertSame('camel-cased', Helper::toSlug('camelCased'));
    }

    public function testAllToSlug(): void
    {
        self::assertSame(
            ['some-words', 'studly-cased', 'snake-cased', 'camel-cased'],
            Helper::allToSlug(self::VALUE, 'StudlyCased', 'snake_cased', 'camelCased')
        );
    }

    public function testToStudlyCase(): void
    {
        self::assertSame('SomeWords', Helper::toStudlyCase(self::VALUE));
        self::assertSame('SlugCased', Helper::toStudlyCase('slug-cased'));
        self::assertSame('SnakeCased', Helper::toStudlyCase('snake_cased'));
        self::assertSame('CamelCased', Helper::toStudlyCase('camelCased'));
    }

    public function testAllToStudlyCase(): void
    {
        self::assertSame(
            ['SomeWords', 'SlugCased', 'SnakeCased', 'CamelCased'],
            Helper::allToStudlyCase(self::VALUE, 'slug-cased', 'snake_cased', 'camelCased')
        );
    }

    public function testUcFirstLetter(): void
    {
        self::assertSame('Fox', Helper::ucFirstLetter('fox'));
        self::assertSame('Some words', Helper::ucFirstLetter('some words'));
    }

    public function testAllUcFirstLetter(): void
    {
        self::assertSame(
            ['Fox', 'Some words'],
            Helper::allUcFirstLetter('fox', 'some words')
        );
    }
}
