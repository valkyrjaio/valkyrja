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
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class NotEmptyTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new NotEmpty('value', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new NotEmpty('test', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithNonEmptyString(): void
    {
        $rule = new NotEmpty('hello', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNonEmptyArray(): void
    {
        $rule = new NotEmpty(['item'], errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNonZeroNumber(): void
    {
        $rule = new NotEmpty(42, errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithTrue(): void
    {
        $rule = new NotEmpty(true, errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new NotEmpty(null, errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new NotEmpty('', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyArray(): void
    {
        $rule = new NotEmpty([], errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithZero(): void
    {
        $rule = new NotEmpty(0, errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithFalse(): void
    {
        $rule = new NotEmpty(false, errorMessage: ErrorMessage::IS_NOT_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithNonEmptyValue(): void
    {
        $rule = new NotEmpty('value', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithEmptyValue(): void
    {
        $rule = new NotEmpty('', errorMessage: ErrorMessage::IS_NOT_EMPTY);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::IS_NOT_EMPTY);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new NotEmpty('', 'Field cannot be empty');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field cannot be empty');

        $rule->validate();
    }
}
