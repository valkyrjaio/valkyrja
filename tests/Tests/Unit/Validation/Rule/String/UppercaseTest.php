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
use Valkyrja\Validation\Rule\String\Uppercase;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class UppercaseTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Uppercase('ABC', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Uppercase('TEST', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertSame('TEST', $rule->getSubject());
    }

    public function testIsValidWithUppercase(): void
    {
        $rule = new Uppercase('HELLO', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithUppercaseAndNumbers(): void
    {
        $rule = new Uppercase('ABC123', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithUppercaseAndSpaces(): void
    {
        $rule = new Uppercase('HELLO WORLD', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithLowercase(): void
    {
        $rule = new Uppercase('hello', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithMixedCase(): void
    {
        $rule = new Uppercase('Hello', errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Uppercase(123, errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Uppercase(null, errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Uppercase([], errorMessage: ErrorMessage::STRING_UPPERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithUppercase(): void
    {
        $rule = new Uppercase('HELLO WORLD', errorMessage: ErrorMessage::STRING_UPPERCASE);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonUppercase(): void
    {
        $rule = new Uppercase('Hello', errorMessage: ErrorMessage::STRING_UPPERCASE);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::STRING_UPPERCASE);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Uppercase('hello', 'Field must be uppercase');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be uppercase');

        $rule->validate();
    }
}
