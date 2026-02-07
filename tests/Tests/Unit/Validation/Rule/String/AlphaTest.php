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

namespace Valkyrja\Tests\Unit\Validation\Rule\String;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\String\Alpha;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class AlphaTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Alpha('abc');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Alpha('test');

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithLowercaseLetters(): void
    {
        $rule = new Alpha('abc');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithUppercaseLetters(): void
    {
        $rule = new Alpha('ABC');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithMixedCaseLetters(): void
    {
        $rule = new Alpha('AbCdEf');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNumbers(): void
    {
        $rule = new Alpha('abc123');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSpecialCharacters(): void
    {
        $rule = new Alpha('abc!@#');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSpaces(): void
    {
        $rule = new Alpha('hello world');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Alpha(123);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Alpha(null);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Alpha([]);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithAlphabetic(): void
    {
        $rule = new Alpha('HelloWorld');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonAlphabetic(): void
    {
        $rule = new Alpha('hello123');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be alphabetic');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Alpha('abc123', 'Only letters allowed');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Only letters allowed');

        $rule->validate();
    }
}
