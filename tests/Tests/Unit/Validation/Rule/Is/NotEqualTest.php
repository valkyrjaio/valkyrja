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
use Valkyrja\Validation\Rule\Is\NotEqual;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class NotEqualTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new NotEqual('a', 'b', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new NotEqual('test', 'other', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithDifferentStrings(): void
    {
        $rule = new NotEqual('hello', 'world', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithDifferentIntegers(): void
    {
        $rule = new NotEqual(1, 2, errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithDifferentTypes(): void
    {
        // Strict comparison: '42' !== 42
        $rule = new NotEqual('42', 42, errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNullVsEmptyString(): void
    {
        $rule = new NotEqual(null, '', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithEqualStrings(): void
    {
        $rule = new NotEqual('hello', 'hello', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEqualIntegers(): void
    {
        $rule = new NotEqual(42, 42, errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEqualNull(): void
    {
        $rule = new NotEqual(null, null, errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEqualArrays(): void
    {
        $rule = new NotEqual(['a', 'b'], ['a', 'b'], errorMessage: ErrorMessage::IS_NOT_EQUAL);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithUnequalValues(): void
    {
        $rule = new NotEqual('foo', 'bar', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithEqualValues(): void
    {
        $rule = new NotEqual('same', 'same', errorMessage: ErrorMessage::IS_NOT_EQUAL);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::IS_NOT_EQUAL);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new NotEqual('x', 'x', 'Values must be different');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Values must be different');

        $rule->validate();
    }
}
