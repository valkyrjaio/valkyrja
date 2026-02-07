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
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class IsNumericTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new IsNumeric(42);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new IsNumeric(42);

        self::assertSame(42, $rule->getSubject());
    }

    public function testIsValidWithInteger(): void
    {
        $rule = new IsNumeric(42);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithFloat(): void
    {
        $rule = new IsNumeric(3.14);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNumericString(): void
    {
        $rule = new IsNumeric('42');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithNegativeNumber(): void
    {
        $rule = new IsNumeric(-10);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithZero(): void
    {
        $rule = new IsNumeric(0);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithScientificNotation(): void
    {
        $rule = new IsNumeric('1e10');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNonNumericString(): void
    {
        $rule = new IsNumeric('hello');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithBoolean(): void
    {
        $rule = new IsNumeric(true);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new IsNumeric(null);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new IsNumeric([]);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithNumeric(): void
    {
        $rule = new IsNumeric(42);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonNumeric(): void
    {
        $rule = new IsNumeric('hello');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be numeric');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new IsNumeric('abc', 'Field must be numeric');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be numeric');

        $rule->validate();
    }
}
