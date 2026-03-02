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
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\String\EndsWith;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class EndsWithTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new EndsWith('hello world', 'world', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new EndsWith('test string', 'string', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertSame('test string', $rule->getSubject());
    }

    public function testIsValidWithCorrectSuffix(): void
    {
        $rule = new EndsWith('hello world', 'world', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithExactMatch(): void
    {
        $rule = new EndsWith('hello', 'hello', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithSingleCharSuffix(): void
    {
        $rule = new EndsWith('hello', 'o', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithWrongSuffix(): void
    {
        $rule = new EndsWith('hello world', 'hello', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithCaseMismatch(): void
    {
        $rule = new EndsWith('hello world', 'WORLD', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithSubstringInMiddle(): void
    {
        $rule = new EndsWith('hello world', 'lo wo', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNonString(): void
    {
        $rule = new EndsWith(123, 'test', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new EndsWith(null, 'test', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new EndsWith([], 'test', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithCorrectSuffix(): void
    {
        $rule = new EndsWith('hello world', 'world', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithWrongSuffix(): void
    {
        $rule = new EndsWith('hello world', 'hello', errorMessage: ErrorMessage::STRING_ENDS_WITH);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(ErrorMessage::STRING_ENDS_WITH);

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
