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
use Valkyrja\Validation\Rule\String\StartsWith;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class StartsWithTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new StartsWith('hello world', 'hello');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new StartsWith('test string', 'test');

        self::assertSame('test string', $rule->getSubject());
    }

    public function testIsValidWithCorrectPrefix(): void
    {
        $rule = new StartsWith('hello world', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithExactMatch(): void
    {
        $rule = new StartsWith('hello', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSingleCharPrefix(): void
    {
        $rule = new StartsWith('hello', 'h');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithWrongPrefix(): void
    {
        $rule = new StartsWith('hello world', 'world');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithCaseMismatch(): void
    {
        $rule = new StartsWith('hello world', 'HELLO');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSubstringInMiddle(): void
    {
        $rule = new StartsWith('hello world', 'lo wo');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new StartsWith(123, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new StartsWith(null, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new StartsWith([], 'test');

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithCorrectPrefix(): void
    {
        $rule = new StartsWith('hello world', 'hello');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithWrongPrefix(): void
    {
        $rule = new StartsWith('hello world', 'world');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must start with world');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new StartsWith('foo', 'bar', 'Field must start with "bar"');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must start with "bar"');

        $rule->validate();
    }
}
