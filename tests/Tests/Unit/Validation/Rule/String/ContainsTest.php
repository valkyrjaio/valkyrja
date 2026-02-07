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
use Valkyrja\Validation\Rule\String\Contains;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class ContainsTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Contains('hello world', 'world');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Contains('test string', 'test');

        self::assertSame('test string', $rule->getSubject());
    }

    public function testIsValidWithContainedSubstring(): void
    {
        $rule = new Contains('hello world', 'world');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithContainedSubstringAtStart(): void
    {
        $rule = new Contains('hello world', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithContainedSubstringInMiddle(): void
    {
        $rule = new Contains('hello beautiful world', 'beautiful');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithExactMatch(): void
    {
        $rule = new Contains('hello', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithoutSubstring(): void
    {
        $rule = new Contains('hello world', 'foo');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithCaseMismatch(): void
    {
        $rule = new Contains('hello world', 'WORLD');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Contains(123, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Contains(null, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Contains([], 'test');

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithContainedSubstring(): void
    {
        $rule = new Contains('hello world', 'world');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithoutSubstring(): void
    {
        $rule = new Contains('hello world', 'foo');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must contain foo');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Contains('hello', 'world', 'Field must contain "world"');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must contain "world"');

        $rule->validate();
    }
}
