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
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class IsStringTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new IsString('value');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new IsString('test');

        self::assertSame('test', $rule->getSubject());
    }

    public function testIsValidWithString(): void
    {
        $rule = new IsString('hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithEmptyString(): void
    {
        $rule = new IsString('');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithInteger(): void
    {
        $rule = new IsString(42);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithFloat(): void
    {
        $rule = new IsString(3.14);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithBoolean(): void
    {
        $rule = new IsString(true);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new IsString([]);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new IsString(null);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithString(): void
    {
        $rule = new IsString('value');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonString(): void
    {
        $rule = new IsString(123);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be a string');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new IsString(123, 'Field must be a string');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be a string');

        $rule->validate();
    }
}
