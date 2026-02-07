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
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\Is\Email;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class EmailTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Email('test@test.com');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Email('test@example.com');

        self::assertSame('test@example.com', $rule->getSubject());
    }

    public function testIsValidWithSimpleEmail(): void
    {
        $rule = new Email('test@example.com');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSubdomain(): void
    {
        $rule = new Email('user@mail.example.com');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithPlusSign(): void
    {
        $rule = new Email('user+tag@example.com');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithDots(): void
    {
        $rule = new Email('first.last@example.com');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithoutAtSign(): void
    {
        $rule = new Email('notanemail');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithoutDomain(): void
    {
        $rule = new Email('user@');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithoutLocalPart(): void
    {
        $rule = new Email('@example.com');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithInteger(): void
    {
        $rule = new Email(123);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Email(null);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new Email('');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Email([]);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithValidEmail(): void
    {
        $rule = new Email('valid@email.com');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithInvalidEmail(): void
    {
        $rule = new Email('invalid');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be a valid email');

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
