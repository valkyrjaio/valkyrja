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
use Valkyrja\Validation\Rule\Int\LessThan;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class LessThanTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new LessThan(5, 10);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new LessThan(5, 10);

        self::assertSame(5, $rule->getSubject());
    }

    public function testIsValidWithLesserValue(): void
    {
        $rule = new LessThan(5, 10);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithMuchLesserValue(): void
    {
        $rule = new LessThan(1, 1000);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithEqualValue(): void
    {
        $rule = new LessThan(5, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithGreaterValue(): void
    {
        $rule = new LessThan(10, 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsValidWithNegativeComparison(): void
    {
        $rule = new LessThan(-10, -5);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNegativeLesserValue(): void
    {
        $rule = new LessThan(-5, -10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithString(): void
    {
        $rule = new LessThan('5', 10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithFloat(): void
    {
        $rule = new LessThan(5.5, 10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new LessThan(null, 10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new LessThan([], 10);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithLesserValue(): void
    {
        $rule = new LessThan(5, 10);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithGreaterValue(): void
    {
        $rule = new LessThan(10, 5);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be less than 5');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new LessThan(10, 5, 'Value must be less than 5');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Value must be less than 5');

        $rule->validate();
    }

    public function testWithZero(): void
    {
        $ruleNegative = new LessThan(-1, 0);
        $rulePositive = new LessThan(1, 0);

        self::assertTrue($ruleNegative->isValid());
        self::assertFalse($rulePositive->isValid());
    }
}
