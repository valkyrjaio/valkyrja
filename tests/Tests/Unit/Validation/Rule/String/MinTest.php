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

namespace Valkyrja\Tests\Unit\Validation\Rule\String;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\String\Min;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class MinTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Min('hello', 3);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Min('test', 2);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithExactMinLength(): void
    {
        $rule = new Min('hello', 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithLongerThanMinLength(): void
    {
        $rule = new Min('hello world', 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithShorterThanMinLength(): void
    {
        $rule = new Min('hi', 5);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new Min('', 1);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Min(12345, 3);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Min(null, 1);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Min(['a', 'b', 'c'], 2);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithValidLength(): void
    {
        $rule = new Min('hello', 3);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithInvalidLength(): void
    {
        $rule = new Min('hi', 5);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be longer than 5');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Min('ab', 3, 'Minimum 3 characters required');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Minimum 3 characters required');

        $rule->validate();
    }

    public function testMinZero(): void
    {
        $rule = new Min('', 0);

        self::assertTrue($rule->isValid());
    }
}
