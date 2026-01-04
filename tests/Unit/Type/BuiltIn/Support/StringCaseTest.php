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
use Valkyrja\Type\BuiltIn\Support\StrCase;

class StringCaseTest extends TestCase
{
    protected const string VALUE     = 'Some Words';
    protected const string UPPERCASE = 'UPPERCASE';
    protected const string LOWERCASE = 'lowercase';

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', StrCase::toTitleCase(self::VALUE));
        self::assertSame('UPPERCASE', StrCase::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', StrCase::toTitleCase(self::LOWERCASE));
    }

    public function testAllToTitleCase(): void
    {
        self::assertSame(
            ['Some Words', 'UPPERCASE', 'Lowercase'],
            StrCase::allToTitleCase(self::VALUE, self::UPPERCASE, self::LOWERCASE)
        );
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', StrCase::toLowerCase(self::VALUE));
        self::assertSame('uppercase', StrCase::toLowerCase('UPPERCASE'));
    }

    public function testAllToLowerCase(): void
    {
        self::assertSame(
            ['some words', 'uppercase'],
            StrCase::allToLowerCase(self::VALUE, 'UPPERCASE')
        );
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', StrCase::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', StrCase::toUpperCase('lowercase'));
    }

    public function testAllToUpperCase(): void
    {
        self::assertSame(
            ['SOME WORDS', 'LOWERCASE'],
            StrCase::allToUpperCase(self::VALUE, 'lowercase')
        );
    }

    public function testToCapitalized(): void
    {
        self::assertSame('Some Words', StrCase::toCapitalized('some words'));
        self::assertSame('Lowercase', StrCase::toCapitalized('lowercase'));
        self::assertSame('Lowercase-Fire', StrCase::toCapitalized('lowercase-fire', '-'));
    }

    public function testAllToCapitalized(): void
    {
        self::assertSame(
            ['Some Words', 'Lowercase'],
            StrCase::allToCapitalized('some words', 'lowercase')
        );
    }

    public function testToCapitalizedWords(): void
    {
        self::assertSame('Some Words', StrCase::toCapitalizedWords('some words'));
        self::assertSame('StudlyCased', StrCase::toCapitalizedWords('StudlyCased'));
        self::assertSame('CamelCase', StrCase::toCapitalizedWords('camelCase'));
        self::assertSame('Slug Cased', StrCase::toCapitalizedWords('slug-cased'));
        self::assertSame('Snake Cased', StrCase::toCapitalizedWords('snake_cased'));
    }

    public function testAllToCapitalizedWords(): void
    {
        self::assertSame(
            ['Some Words', 'StudlyCased', 'CamelCase', 'Slug Cased', 'Snake Cased'],
            StrCase::allToCapitalizedWords('some words', 'StudlyCased', 'camelCase', 'slug-cased', 'snake_cased')
        );
    }

    public function testToSnakeCase(): void
    {
        self::assertSame('some_words', StrCase::toSnakeCase(self::VALUE));
        self::assertSame('studly_cased', StrCase::toSnakeCase('StudlyCased'));
        self::assertSame('slug_cased', StrCase::toSnakeCase('slug-cased'));
    }

    public function testAllToSnakeCase(): void
    {
        self::assertSame(
            ['some_words', 'studly_cased', 'slug_cased'],
            StrCase::allToSnakeCase(self::VALUE, 'StudlyCased', 'slug-cased')
        );
    }

    public function testToSlug(): void
    {
        self::assertSame('some-words', StrCase::toSlug(self::VALUE));
        self::assertSame('studly-cased', StrCase::toSlug('StudlyCased'));
        self::assertSame('snake-cased', StrCase::toSlug('snake_cased'));
        self::assertSame('camel-cased', StrCase::toSlug('camelCased'));
    }

    public function testAllToSlug(): void
    {
        self::assertSame(
            ['some-words', 'studly-cased', 'snake-cased', 'camel-cased'],
            StrCase::allToSlug(self::VALUE, 'StudlyCased', 'snake_cased', 'camelCased')
        );
    }

    public function testToStudlyCase(): void
    {
        self::assertSame('SomeWords', StrCase::toStudlyCase(self::VALUE));
        self::assertSame('SlugCased', StrCase::toStudlyCase('slug-cased'));
        self::assertSame('SnakeCased', StrCase::toStudlyCase('snake_cased'));
        self::assertSame('CamelCased', StrCase::toStudlyCase('camelCased'));
    }

    public function testAllToStudlyCase(): void
    {
        self::assertSame(
            ['SomeWords', 'SlugCased', 'SnakeCased', 'CamelCased'],
            StrCase::allToStudlyCase(self::VALUE, 'slug-cased', 'snake_cased', 'camelCased')
        );
    }

    public function testUcFirstLetter(): void
    {
        self::assertSame('Fox', StrCase::ucFirstLetter('fox'));
        self::assertSame('Some words', StrCase::ucFirstLetter('some words'));
    }

    public function testAllUcFirstLetter(): void
    {
        self::assertSame(
            ['Fox', 'Some words'],
            StrCase::allUcFirstLetter('fox', 'some words')
        );
    }
}
