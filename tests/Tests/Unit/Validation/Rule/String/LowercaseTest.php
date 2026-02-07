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
use Valkyrja\Validation\Rule\String\Lowercase;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class LowercaseTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new Lowercase('abc');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new Lowercase('test');

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithLowercase(): void
    {
        $rule = new Lowercase('hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithLowercaseAndNumbers(): void
    {
        $rule = new Lowercase('abc123');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithLowercaseAndSpaces(): void
    {
        $rule = new Lowercase('hello world');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithUppercase(): void
    {
        $rule = new Lowercase('HELLO');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithMixedCase(): void
    {
        $rule = new Lowercase('Hello');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new Lowercase(123);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new Lowercase(null);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new Lowercase([]);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithLowercase(): void
    {
        $rule = new Lowercase('hello world');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonLowercase(): void
    {
        $rule = new Lowercase('Hello');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be lowercase');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new Lowercase('HELLO', 'Field must be lowercase');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be lowercase');

        $rule->validate();
    }
}
