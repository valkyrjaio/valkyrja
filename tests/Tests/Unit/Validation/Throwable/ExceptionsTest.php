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
use Throwable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Validation\Throwable\Contract\ValidationThrowable;
use Valkyrja\Validation\Throwable\Exception\ValidationInvalidArgumentException;
use Valkyrja\Validation\Throwable\Exception\ValidationRuleException;
use Valkyrja\Validation\Throwable\Exception\ValidationRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(ValidationThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new ValidationRuntimeException('Runtime error');

        self::assertInstanceOf(ValidationThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new ValidationRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new ValidationRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testValidationExceptionImplementsThrowable(): void
    {
        $exception = new ValidationRuleException('Validation failed');

        self::assertInstanceOf(ValidationThrowable::class, $exception);
        self::assertInstanceOf(ValidationRuntimeException::class, $exception);
    }

    public function testValidationExceptionMessage(): void
    {
        $message   = 'Field is required';
        $exception = new ValidationRuleException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testValidationExceptionCanBeThrown(): void
    {
        $this->expectException(ValidationRuleException::class);
        $this->expectExceptionMessage('Must be valid');

        throw new ValidationRuleException('Must be valid');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new ValidationInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(ValidationThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid argument provided';
        $exception = new ValidationInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(ValidationInvalidArgumentException::class);
        $this->expectExceptionMessage('Argument is not valid');

        throw new ValidationInvalidArgumentException('Argument is not valid');
    }

    public function testExceptionHierarchy(): void
    {
        // ValidationException extends RuntimeException
        self::assertTrue(is_a(ValidationRuleException::class, ValidationRuntimeException::class, true));

        // Both implement Throwable
        self::assertTrue(is_a(ValidationRuleException::class, ValidationThrowable::class, true));
        self::assertTrue(is_a(ValidationRuntimeException::class, ValidationThrowable::class, true));
        self::assertTrue(is_a(ValidationInvalidArgumentException::class, ValidationThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new ValidationRuleException('Validation failed', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
