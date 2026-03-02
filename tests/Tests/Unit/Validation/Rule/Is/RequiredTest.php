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
use Valkyrja\Validation\Rule\Is\Required;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class RequiredTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Required('value', errorMessage: ErrorMessage::REQUIRED);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Required('test', errorMessage: ErrorMessage::REQUIRED);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithTruthyString(): void
    {
        $rule = new Required('hello', errorMessage: ErrorMessage::REQUIRED);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithTruthyNumber(): void
    {
        $rule = new Required(42, errorMessage: ErrorMessage::REQUIRED);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithTrue(): void
    {
        $rule = new Required(true, errorMessage: ErrorMessage::REQUIRED);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNonEmptyArray(): void
    {
        $rule = new Required(['item'], errorMessage: ErrorMessage::REQUIRED);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new Required('', errorMessage: ErrorMessage::REQUIRED);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Required(null, errorMessage: ErrorMessage::REQUIRED);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithFalse(): void
    {
        $rule = new Required(false, errorMessage: ErrorMessage::REQUIRED);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithZero(): void
    {
        $rule = new Required(0, errorMessage: ErrorMessage::REQUIRED);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyArray(): void
    {
        $rule = new Required([], errorMessage: ErrorMessage::REQUIRED);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithTruthyValue(): void
    {
        $rule = new Required('value', errorMessage: ErrorMessage::REQUIRED);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithFalsyValue(): void
    {
        $rule = new Required('', errorMessage: ErrorMessage::REQUIRED);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::REQUIRED);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Required('', 'Field is required');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field is required');

        $rule->validate();
    }
}
