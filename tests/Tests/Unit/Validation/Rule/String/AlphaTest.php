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
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\String\Alpha;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class AlphaTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Alpha('abc', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Alpha('test', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithLowercaseLetters(): void
    {
        $rule = new Alpha('abc', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithUppercaseLetters(): void
    {
        $rule = new Alpha('ABC', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithMixedCaseLetters(): void
    {
        $rule = new Alpha('AbCdEf', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNumbers(): void
    {
        $rule = new Alpha('abc123', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSpecialCharacters(): void
    {
        $rule = new Alpha('abc!@#', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSpaces(): void
    {
        $rule = new Alpha('hello world', errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Alpha(123, errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Alpha(null, errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Alpha([], errorMessage: ErrorMessage::STRING_ALPHA);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithAlphabetic(): void
    {
        $rule = new Alpha('HelloWorld', errorMessage: ErrorMessage::STRING_ALPHA);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonAlphabetic(): void
    {
        $rule = new Alpha('hello123', errorMessage: ErrorMessage::STRING_ALPHA);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::STRING_ALPHA);

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
