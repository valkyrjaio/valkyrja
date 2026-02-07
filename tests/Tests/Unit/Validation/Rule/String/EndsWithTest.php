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
use Valkyrja\Validation\Rule\String\EndsWith;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

class EndsWithTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new EndsWith('hello world', 'world');

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new EndsWith('test string', 'string');

        self::assertSame('test string', $rule->getSubject());
    }

    public function testIsValidWithCorrectSuffix(): void
    {
        $rule = new EndsWith('hello world', 'world');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithExactMatch(): void
    {
        $rule = new EndsWith('hello', 'hello');

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSingleCharSuffix(): void
    {
        $rule = new EndsWith('hello', 'o');

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithWrongSuffix(): void
    {
        $rule = new EndsWith('hello world', 'hello');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithCaseMismatch(): void
    {
        $rule = new EndsWith('hello world', 'WORLD');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSubstringInMiddle(): void
    {
        $rule = new EndsWith('hello world', 'lo wo');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new EndsWith(123, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new EndsWith(null, 'test');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new EndsWith([], 'test');

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithCorrectSuffix(): void
    {
        $rule = new EndsWith('hello world', 'world');

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithWrongSuffix(): void
    {
        $rule = new EndsWith('hello world', 'hello');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must end with hello');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new EndsWith('foo', 'bar', 'Field must end with "bar"');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must end with "bar"');

        $rule->validate();
    }
}
