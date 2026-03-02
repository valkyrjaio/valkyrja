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

namespace Valkyrja\Tests\Unit\Validation\Rule\Is;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\Is\Email;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class EmailTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Email('test@test.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Email('test@example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertSame('test@example.com', $rule->getSubject());
    }

    public function testIsValidWithSimpleEmail(): void
    {
        $rule = new Email('test@example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSubdomain(): void
    {
        $rule = new Email('user@mail.example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithPlusSign(): void
    {
        $rule = new Email('user+tag@example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithDots(): void
    {
        $rule = new Email('first.last@example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithoutAtSign(): void
    {
        $rule = new Email('notanemail', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithoutDomain(): void
    {
        $rule = new Email('user@', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithoutLocalPart(): void
    {
        $rule = new Email('@example.com', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithInteger(): void
    {
        $rule = new Email(123, errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Email(null, errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new Email('', errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Email([], errorMessage: ErrorMessage::IS_EMAIL);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithValidEmail(): void
    {
        $rule = new Email('valid@email.com', errorMessage: ErrorMessage::IS_EMAIL);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithInvalidEmail(): void
    {
        $rule = new Email('invalid', errorMessage: ErrorMessage::IS_EMAIL);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::IS_EMAIL);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Email('bad', 'Please provide a valid email address');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Please provide a valid email address');

        $rule->validate();
    }
}
