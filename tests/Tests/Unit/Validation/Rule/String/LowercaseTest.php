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
use Valkyrja\Validation\Rule\String\Lowercase;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class LowercaseTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Lowercase('abc', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Lowercase('test', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithLowercase(): void
    {
        $rule = new Lowercase('hello', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithLowercaseAndNumbers(): void
    {
        $rule = new Lowercase('abc123', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithLowercaseAndSpaces(): void
    {
        $rule = new Lowercase('hello world', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithUppercase(): void
    {
        $rule = new Lowercase('HELLO', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithMixedCase(): void
    {
        $rule = new Lowercase('Hello', errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Lowercase(123, errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Lowercase(null, errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Lowercase([], errorMessage: ErrorMessage::STRING_LOWERCASE);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithLowercase(): void
    {
        $rule = new Lowercase('hello world', errorMessage: ErrorMessage::STRING_LOWERCASE);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonLowercase(): void
    {
        $rule = new Lowercase('Hello', errorMessage: ErrorMessage::STRING_LOWERCASE);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::STRING_LOWERCASE);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Lowercase('HELLO', 'Field must be lowercase');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be lowercase');

        $rule->validate();
    }
}
