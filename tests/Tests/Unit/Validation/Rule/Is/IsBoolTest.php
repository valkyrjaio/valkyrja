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
use Valkyrja\Validation\Rule\Is\IsBool;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class IsBoolTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $rule = new IsBool(true);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $rule = new IsBool(true);

        self::assertTrue($rule->getSubject());
    }

    public function testIsValidWithTrue(): void
    {
        $rule = new IsBool(true);

        self::assertTrue($rule->isValid());
    }

    public function testIsValidWithFalse(): void
    {
        $rule = new IsBool(false);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWithInteger(): void
    {
        $rule = new IsBool(1);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithZero(): void
    {
        $rule = new IsBool(0);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithString(): void
    {
        $rule = new IsBool('true');

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithNull(): void
    {
        $rule = new IsBool(null);

        self::assertFalse($rule->isValid());
    }

    public function testIsInvalidWithArray(): void
    {
        $rule = new IsBool([]);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWithBoolean(): void
    {
        $rule = new IsBool(true);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWithNonBoolean(): void
    {
        $rule = new IsBool('true');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be a boolean');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $rule = new IsBool(1, 'Field must be a boolean');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Field must be a boolean');

        $rule->validate();
    }
}
