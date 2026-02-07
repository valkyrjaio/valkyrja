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

namespace Valkyrja\Tests\Unit\Type\String;

use stdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\String\StringT;

use function json_encode;

class StringTest extends TestCase
{
    protected const string VALUE = 'foo';

    public function testValue(): void
    {
        $type = new StringT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = StringT::fromValue(self::VALUE);
        $array         = ['foo' => 'bar'];
        $obj           = new stdClass();
        $obj->foo      = 'bar';

        self::assertSame(self::VALUE, $typeFromValue->asValue());
        self::assertSame('{"foo":"bar"}', StringT::fromValue($array)->asValue());
        self::assertSame('{"foo":"bar"}', StringT::fromValue($obj)->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new StringT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new StringT(self::VALUE);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static fn (string $subject): string => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new StringT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }

    public function testStartsWith(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->startsWith('Some'));
        self::assertFalse($someWords->startsWith('nope'));
    }

    public function testStartsWithAny(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->startsWithAny('nope', 'Some'));
        self::assertFalse($someWords->startsWithAny('nope', 'no'));
    }

    public function testEndsWith(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->endsWith('Words'));
        self::assertFalse($someWords->endsWith('nope'));
    }

    public function testEndsWithAny(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->endsWithAny('nope', 'Words'));
        self::assertFalse($someWords->endsWithAny('nope', 'no'));
    }

    public function testContains(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->contains('Some'));
        self::assertFalse($someWords->contains('nope'));
    }

    public function testContainsAny(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->containsAny('nope', 'Some'));
        self::assertFalse($someWords->containsAny('nope', 'no'));
    }

    public function testMin(): void
    {
        $someWords = new StringT('Some Words');

        self::assertTrue($someWords->min(2));
        self::assertTrue($someWords->min(10));
        self::assertFalse($someWords->min(11));
        self::assertFalse($someWords->min(20));
    }

    public function testMax(): void
    {
        $someWords = new StringT('Some Words');

        self::assertFalse($someWords->max(2));
        self::assertFalse($someWords->max(9));
        self::assertTrue($someWords->max(10));
        self::assertTrue($someWords->max(20));
    }

    public function testReplace(): void
    {
        $someWords = new StringT('Some Words');

        self::assertSame('Stme Wtrds', $someWords->replace('o', 't')->asValue());
    }

    public function testReplaceAll(): void
    {
        $someWords = new StringT('Some Words');

        self::assertSame('Stmm Wtrds', $someWords->replaceAll(['o', 'e'], ['t', 'm'])->asValue());
    }

    public function testReplaceAllWith(): void
    {
        $someWords = new StringT('Some Words');

        self::assertSame('Stmt Wtrds', $someWords->replaceAllWith(['o', 'e'], 't')->asValue());
    }

    public function testSubstr(): void
    {
        $someWords = new StringT('Some Words');

        self::assertSame('Some', $someWords->substr(0, 4)->asValue());
    }

    public function testToTitleCase(): void
    {
        $someWords = new StringT('Some Words');
        $uppercase = new StringT('UPPERCASE');
        $lowercase = new StringT('lowercase');

        self::assertSame('Some Words', $someWords->toTitleCase()->asValue());
        self::assertSame('UPPERCASE', $uppercase->toTitleCase()->asValue());
        self::assertSame('Lowercase', $lowercase->toTitleCase()->asValue());
    }

    public function testToLowerCase(): void
    {
        $someWords = new StringT('Some Words');
        $uppercase = new StringT('UPPERCASE');

        self::assertSame('some words', $someWords->toLowerCase()->asValue());
        self::assertSame('uppercase', $uppercase->toLowerCase()->asValue());
    }

    public function testToUpperCase(): void
    {
        $someWords = new StringT('Some Words');
        $lowercase = new StringT('lowercase');

        self::assertSame('SOME WORDS', $someWords->toUpperCase()->asValue());
        self::assertSame('LOWERCASE', $lowercase->toUpperCase()->asValue());
    }

    public function testToCapitalized(): void
    {
        $someWords = new StringT('some words');
        $lowercase = new StringT('lowercase');

        self::assertSame('Some Words', $someWords->toCapitalized()->asValue());
        self::assertSame('Lowercase', $lowercase->toCapitalized()->asValue());
    }

    public function testToCapitalizedWords(): void
    {
        $someWords = new StringT('some words');
        $studly    = new StringT('StudlyCased');
        $camel     = new StringT('camelCase');
        $slug      = new StringT('slug-cased');
        $snake     = new StringT('snake_cased');

        self::assertSame('Some Words', $someWords->toCapitalizedWords()->asValue());
        self::assertSame('StudlyCased', $studly->toCapitalizedWords()->asValue());
        self::assertSame('CamelCase', $camel->toCapitalizedWords()->asValue());
        self::assertSame('Slug Cased', $slug->toCapitalizedWords()->asValue());
        self::assertSame('Snake Cased', $snake->toCapitalizedWords()->asValue());
    }

    public function testToSnakeCase(): void
    {
        $someWords = new StringT('Some Words');
        $studly    = new StringT('StudlyCased');
        $slug      = new StringT('slug-cased');

        self::assertSame('some_words', $someWords->toSnakeCase()->asValue());
        self::assertSame('studly_cased', $studly->toSnakeCase()->asValue());
        self::assertSame('slug_cased', $slug->toSnakeCase()->asValue());
    }

    public function testToSlug(): void
    {
        $someWords = new StringT('Some Words');
        $studly    = new StringT('StudlyCased');
        $snake     = new StringT('snake_cased');

        self::assertSame('some-words', $someWords->toSlug()->asValue());
        self::assertSame('studly-cased', $studly->toSlug()->asValue());
        self::assertSame('snake-cased', $snake->toSlug()->asValue());
    }

    public function testToStudlyCase(): void
    {
        $someWords = new StringT('Some Words');
        $slug      = new StringT('slug-cased');
        $snake     = new StringT('snake_cased');

        self::assertSame('SomeWords', $someWords->toStudlyCase()->asValue());
        self::assertSame('SlugCased', $slug->toStudlyCase()->asValue());
        self::assertSame('SnakeCased', $snake->toStudlyCase()->asValue());
    }

    public function testUcFirstLetter(): void
    {
        $fox       = new StringT('fox');
        $someWords = new StringT('some words');

        self::assertSame('Fox', $fox->ucFirstLetter()->asValue());
        self::assertSame('Some words', $someWords->ucFirstLetter()->asValue());
    }

    public function testIsEmail(): void
    {
        $email    = new StringT('test@test.com');
        $notEmail = new StringT('not');

        self::assertTrue($email->isEmail());
        self::assertFalse($notEmail->isEmail());
    }

    public function testIsAlphabetic(): void
    {
        $alphabetic   = new StringT('abc');
        $alphanumeric = new StringT('abc123');

        self::assertTrue($alphabetic->isAlphabetic());
        self::assertFalse($alphanumeric->isAlphabetic());
    }

    public function testIsLowercase(): void
    {
        $lowercase   = new StringT('lowercase');
        $capitalized = new StringT('Capitalized');
        $uppercase   = new StringT('UPPERCASE');

        self::assertTrue($lowercase->isLowercase());
        self::assertFalse($capitalized->isLowercase());
        self::assertFalse($uppercase->isLowercase());
    }

    public function testIsUppercase(): void
    {
        $lowercase   = new StringT('lowercase');
        $capitalized = new StringT('Capitalized');
        $uppercase   = new StringT('UPPERCASE');

        self::assertFalse($lowercase->isUppercase());
        self::assertFalse($capitalized->isUppercase());
        self::assertTrue($uppercase->isUppercase());
    }
}
