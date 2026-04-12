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

namespace Valkyrja\Tests\Unit\Log\Throwable;

use InvalidArgumentException;
use RuntimeException;
use Throwable;
use Valkyrja\Log\Throwable\Contract\LogThrowable;
use Valkyrja\Log\Throwable\Exception\LogInvalidArgumentException;
use Valkyrja\Log\Throwable\Exception\LogRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(LogThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new LogInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(LogThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid log level provided';
        $exception = new LogInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCode(): void
    {
        $code      = 400;
        $exception = new LogInvalidArgumentException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(LogInvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid log level');

        throw new LogInvalidArgumentException('Invalid log level');
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new LogRuntimeException('Runtime error');

        self::assertInstanceOf(LogThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'Failed to write to log file';
        $exception = new LogRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new LogRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(LogRuntimeException::class);
        $this->expectExceptionMessage('Log operation failed');

        throw new LogRuntimeException('Log operation failed');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(LogInvalidArgumentException::class, LogThrowable::class, true));
        self::assertTrue(is_a(LogRuntimeException::class, LogThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new LogRuntimeException('Log error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
