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
use Valkyrja\Validation\Rule\Is\Equal;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class EqualTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Equal('value', 'value');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Equal('test', 'test');

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithEqualStrings(): void
    {
        $rule = new Equal('hello', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEqualIntegers(): void
    {
        $rule = new Equal(42, 42);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEqualNull(): void
    {
        $rule = new Equal(null, null);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEqualArrays(): void
    {
        $rule = new Equal(['a', 'b'], ['a', 'b']);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithDifferentStrings(): void
    {
        $rule = new Equal('hello', 'world');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithDifferentTypes(): void
    {
        // Strict comparison: '42' !== 42
        $rule = new Equal('42', 42);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithDifferentIntegers(): void
    {
        $rule = new Equal(1, 2);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNullVsEmptyString(): void
    {
        $rule = new Equal(null, '');

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithEqualValues(): void
    {
        $rule = new Equal('value', 'value');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithUnequalValues(): void
    {
        $rule = new Equal('foo', 'bar');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must equal');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Equal('a', 'b', 'Values must match');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Values must match');

        $rule->validate();
    }
}
