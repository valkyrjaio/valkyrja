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

use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;
use Valkyrja\Validation\Throwable\Contract\Throwable;
use Valkyrja\Validation\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Validation\Throwable\Exception\RuntimeException;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(Throwable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new RuntimeException('Runtime error');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(PhpThrowable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new RuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new RuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testValidationExceptionImplementsThrowable(): void
    {
        $exception = new ValidationException('Validation failed');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testValidationExceptionMessage(): void
    {
        $message   = 'Field is required';
        $exception = new ValidationException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testValidationExceptionCanBeThrown(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Must be valid');

        throw new ValidationException('Must be valid');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid argument provided';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument is not valid');

        throw new InvalidArgumentException('Argument is not valid');
    }

    public function testExceptionHierarchy(): void
    {
        // ValidationException extends RuntimeException
        self::assertTrue(is_a(ValidationException::class, RuntimeException::class, true));

        // Both implement Throwable
        self::assertTrue(is_a(ValidationException::class, Throwable::class, true));
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new ValidationException('Validation failed', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
