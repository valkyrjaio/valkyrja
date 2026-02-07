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
use Valkyrja\Validation\Rule\String\Regex;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class RegexTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Regex('test', '/test/');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Regex('test123', '/\d+/');

        self::assertSame('test123', $rule->getSubject());
    }

    public function testIsValidWithMatchingPattern(): void
    {
        $rule = new Regex('test123', '/^[a-z]+\d+$/');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSimpleMatch(): void
    {
        $rule = new Regex('hello', '/hello/');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithPartialMatch(): void
    {
        $rule = new Regex('hello world', '/world/');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEmailPattern(): void
    {
        $rule = new Regex('test@example.com', '/^[^@]+@[^@]+\.[^@]+$/');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithNonMatchingPattern(): void
    {
        $rule = new Regex('abc', '/\d+/');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithEmptyString(): void
    {
        $rule = new Regex('', '/\d+/');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Regex(123, '/\d+/');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Regex(null, '/test/');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Regex([], '/test/');

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithMatchingPattern(): void
    {
        $rule = new Regex('test123', '/^[a-z]+\d+$/');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonMatchingPattern(): void
    {
        $regex = '/^[0-9]+$/';
        $rule  = new Regex('abc', $regex);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Must match the given regex $regex");

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Regex('abc', '/\d+/', 'Field must contain numbers');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must contain numbers');

        $rule->validate();
    }

    public function testCaseInsensitiveMatch(): void
    {
        $rule = new Regex('HELLO', '/hello/i');

        self::assertTrue($rule->isValid());
    }
}
