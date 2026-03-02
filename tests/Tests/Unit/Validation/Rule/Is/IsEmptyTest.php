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
use Valkyrja\Validation\Rule\Is\IsEmpty;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class IsEmptyTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new IsEmpty(null, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new IsEmpty('', errorMessage: ErrorMessage::IS_EMPTY);

        self::assertSame('', $rule->getSubject());
    }

    public function testIsValidWithNull(): void
    {
        $rule = new IsEmpty(null, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEmptyString(): void
    {
        $rule = new IsEmpty('', errorMessage: ErrorMessage::IS_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEmptyArray(): void
    {
        $rule = new IsEmpty([], errorMessage: ErrorMessage::IS_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithZero(): void
    {
        $rule = new IsEmpty(0, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithFalse(): void
    {
        $rule = new IsEmpty(false, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNonEmptyString(): void
    {
        $rule = new IsEmpty('hello', errorMessage: ErrorMessage::IS_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonEmptyArray(): void
    {
        $rule = new IsEmpty(['item'], errorMessage: ErrorMessage::IS_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonZeroNumber(): void
    {
        $rule = new IsEmpty(42, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithTrue(): void
    {
        $rule = new IsEmpty(true, errorMessage: ErrorMessage::IS_EMPTY);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithEmptyValue(): void
    {
        $rule = new IsEmpty('', errorMessage: ErrorMessage::IS_EMPTY);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonEmptyValue(): void
    {
        $rule = new IsEmpty('not empty', errorMessage: ErrorMessage::IS_EMPTY);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::IS_EMPTY);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new IsEmpty('value', 'Field must be empty');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be empty');

        $rule->validate();
    }
}
