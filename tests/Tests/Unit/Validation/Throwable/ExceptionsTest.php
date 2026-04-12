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

namespace Valkyrja\Tests\Unit\Validation\Throwable;

use RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Validation\Throwable\Contract\ValidationThrowable;
use Valkyrja\Validation\Throwable\Exception\Abstract\ValidationInvalidArgumentException;
use Valkyrja\Validation\Throwable\Exception\Abstract\ValidationRuntimeException;
use Valkyrja\Validation\Throwable\Exception\ValidationRuleFailureException;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(ValidationThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testValidationExceptionImplementsThrowable(): void
    {
        $exception = new ValidationRuleFailureException('Validation failed');

        self::assertInstanceOf(ValidationThrowable::class, $exception);
        self::assertInstanceOf(ValidationRuntimeException::class, $exception);
    }

    public function testValidationExceptionMessage(): void
    {
        $message   = 'Field is required';
        $exception = new ValidationRuleFailureException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testValidationExceptionCanBeThrown(): void
    {
        $this->expectException(ValidationRuleFailureException::class);
        $this->expectExceptionMessage('Must be valid');

        throw new ValidationRuleFailureException('Must be valid');
    }

    public function testExceptionHierarchy(): void
    {
        // ValidationException extends RuntimeException
        self::assertTrue(is_a(ValidationRuleFailureException::class, ValidationRuntimeException::class, true));

        // Both implement Throwable
        self::assertTrue(is_a(ValidationRuleFailureException::class, ValidationThrowable::class, true));
        self::assertTrue(is_a(ValidationRuntimeException::class, ValidationThrowable::class, true));
        self::assertTrue(is_a(ValidationInvalidArgumentException::class, ValidationThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new ValidationRuleFailureException('Validation failed', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
