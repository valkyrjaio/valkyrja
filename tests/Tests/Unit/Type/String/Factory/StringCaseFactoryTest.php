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
use Valkyrja\Type\String\Factory\StringCaseFactory;

final class StringCaseFactoryTest extends TestCase
{
    protected const string VALUE     = 'Some Words';
    protected const string UPPERCASE = 'UPPERCASE';
    protected const string LOWERCASE = 'lowercase';

    public function testToTitleCase(): void
    {
        self::assertSame('Some Words', StringCaseFactory::toTitleCase(self::VALUE));
        self::assertSame('UPPERCASE', StringCaseFactory::toTitleCase(self::UPPERCASE));
        self::assertSame('Lowercase', StringCaseFactory::toTitleCase(self::LOWERCASE));
    }

    public function testAllToTitleCase(): void
    {
        self::assertSame(
            ['Some Words', 'UPPERCASE', 'Lowercase'],
            StringCaseFactory::allToTitleCase(self::VALUE, self::UPPERCASE, self::LOWERCASE)
        );
    }

    public function testToLowerCase(): void
    {
        self::assertSame('some words', StringCaseFactory::toLowerCase(self::VALUE));
        self::assertSame('uppercase', StringCaseFactory::toLowerCase('UPPERCASE'));
    }

    public function testAllToLowerCase(): void
    {
        self::assertSame(
            ['some words', 'uppercase'],
            StringCaseFactory::allToLowerCase(self::VALUE, 'UPPERCASE')
        );
    }

    public function testToUpperCase(): void
    {
        self::assertSame('SOME WORDS', StringCaseFactory::toUpperCase(self::VALUE));
        self::assertSame('LOWERCASE', StringCaseFactory::toUpperCase('lowercase'));
    }

    public function testAllToUpperCase(): void
    {
        self::assertSame(
            ['SOME WORDS', 'LOWERCASE'],
            StringCaseFactory::allToUpperCase(self::VALUE, 'lowercase')
        );
    }

    public function testToCapitalized(): void
    {
        self::assertSame('Some Words', StringCaseFactory::toCapitalized('some words'));
        self::assertSame('Lowercase', StringCaseFactory::toCapitalized('lowercase'));
        self::assertSame('Lowercase-Fire', StringCaseFactory::toCapitalized('lowercase-fire', '-'));
    }

    public function testAllToCapitalized(): void
    {
        self::assertSame(
            ['Some Words', 'Lowercase'],
            StringCaseFactory::allToCapitalized('some words', 'lowercase')
        );
    }

    public function testToCapitalizedWords(): void
    {
        self::assertSame('Some Words', StringCaseFactory::toCapitalizedWords('some words'));
        self::assertSame('StudlyCased', StringCaseFactory::toCapitalizedWords('StudlyCased'));
        self::assertSame('CamelCase', StringCaseFactory::toCapitalizedWords('camelCase'));
        self::assertSame('Slug Cased', StringCaseFactory::toCapitalizedWords('slug-cased'));
        self::assertSame('Snake Cased', StringCaseFactory::toCapitalizedWords('snake_cased'));
    }

    public function testAllToCapitalizedWords(): void
    {
        self::assertSame(
            ['Some Words', 'StudlyCased', 'CamelCase', 'Slug Cased', 'Snake Cased'],
            StringCaseFactory::allToCapitalizedWords('some words', 'StudlyCased', 'camelCase', 'slug-cased', 'snake_cased')
        );
    }

    public function testToSnakeCase(): void
    {
        self::assertSame('some_words', StringCaseFactory::toSnakeCase(self::VALUE));
        self::assertSame('studly_cased', StringCaseFactory::toSnakeCase('StudlyCased'));
        self::assertSame('slug_cased', StringCaseFactory::toSnakeCase('slug-cased'));
    }

    public function testAllToSnakeCase(): void
    {
        self::assertSame(
            ['some_words', 'studly_cased', 'slug_cased'],
            StringCaseFactory::allToSnakeCase(self::VALUE, 'StudlyCased', 'slug-cased')
        );
    }

    public function testToSlug(): void
    {
        self::assertSame('some-words', StringCaseFactory::toSlug(self::VALUE));
        self::assertSame('studly-cased', StringCaseFactory::toSlug('StudlyCased'));
        self::assertSame('snake-cased', StringCaseFactory::toSlug('snake_cased'));
        self::assertSame('camel-cased', StringCaseFactory::toSlug('camelCased'));
    }

    public function testAllToSlug(): void
    {
        self::assertSame(
            ['some-words', 'studly-cased', 'snake-cased', 'camel-cased'],
            StringCaseFactory::allToSlug(self::VALUE, 'StudlyCased', 'snake_cased', 'camelCased')
        );
    }

    public function testToStudlyCase(): void
    {
        self::assertSame('SomeWords', StringCaseFactory::toStudlyCase(self::VALUE));
        self::assertSame('SlugCased', StringCaseFactory::toStudlyCase('slug-cased'));
        self::assertSame('SnakeCased', StringCaseFactory::toStudlyCase('snake_cased'));
        self::assertSame('CamelCased', StringCaseFactory::toStudlyCase('camelCased'));
    }

    public function testAllToStudlyCase(): void
    {
        self::assertSame(
            ['SomeWords', 'SlugCased', 'SnakeCased', 'CamelCased'],
            StringCaseFactory::allToStudlyCase(self::VALUE, 'slug-cased', 'snake_cased', 'camelCased')
        );
    }

    public function testUcFirstLetter(): void
    {
        self::assertSame('Fox', StringCaseFactory::ucFirstLetter('fox'));
        self::assertSame('Some words', StringCaseFactory::ucFirstLetter('some words'));
    }

    public function testAllUcFirstLetter(): void
    {
        self::assertSame(
            ['Fox', 'Some words'],
            StringCaseFactory::allUcFirstLetter('fox', 'some words')
        );
    }
}
