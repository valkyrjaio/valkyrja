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

namespace Valkyrja\Tests\Unit\Validation\Rule\Int;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\Int\GreaterThan;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class GreaterThanTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new GreaterThan(10, 5);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new GreaterThan(42, 10);

        self::assertSame(42, $rule->getSubject());
    }

    public function testIsValidWithGreaterValue(): void
    {
        $rule = new GreaterThan(10, 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithMuchGreaterValue(): void
    {
        $rule = new GreaterThan(1000, 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithEqualValue(): void
    {
        $rule = new GreaterThan(5, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithLesserValue(): void
    {
        $rule = new GreaterThan(3, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNegativeComparison(): void
    {
        $rule = new GreaterThan(-10, -5);

        self::assertFalse($rule->isValid());
    }

    public function testIsValidWithNegativeGreaterValue(): void
    {
        $rule = new GreaterThan(-5, -10);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithString(): void
    {
        $rule = new GreaterThan('10', 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithFloat(): void
    {
        $rule = new GreaterThan(10.5, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new GreaterThan(null, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new GreaterThan([], 5);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithGreaterValue(): void
    {
        $rule = new GreaterThan(10, 5);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithLesserValue(): void
    {
        $rule = new GreaterThan(3, 5);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be greater than 5');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new GreaterThan(3, 5, 'Value must be greater than 5');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Value must be greater than 5');

        $rule->validate();
    }

    public function testWithZero(): void
    {
        $rulePositive = new GreaterThan(1, 0);
        $ruleNegative = new GreaterThan(-1, 0);

        self::assertTrue($rulePositive->isValid());
        self::assertFalse($ruleNegative->isValid());
    }
}
