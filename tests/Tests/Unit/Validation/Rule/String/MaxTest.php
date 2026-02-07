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
use Valkyrja\Validation\Rule\String\Max;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class MaxTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Max('hello', 10);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Max('test', 10);

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithExactMaxLength(): void
    {
        $rule = new Max('hello', 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithShorterThanMaxLength(): void
    {
        $rule = new Max('hi', 10);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEmptyString(): void
    {
        $rule = new Max('', 5);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Max(12345, 10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Max(null, 10);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Max(['a', 'b', 'c'], 10);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithValidLength(): void
    {
        $rule = new Max('hello', 10);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithInvalidLength(): void
    {
        $rule = new Max('', 5);

        $rule->validate();

        self::assertTrue(true);
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Max(123, 10, 'Maximum 10 characters allowed');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Maximum 10 characters allowed');

        $rule->validate();
    }

    public function testErrorMessage(): void
    {
        $rule = new Max(123, 10);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must not be longer than 10');

        $rule->validate();
    }
}
