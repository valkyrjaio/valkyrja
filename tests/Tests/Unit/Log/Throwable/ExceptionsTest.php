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

use InvalidArgumentException as PhpInvalidArgumentException;
use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Log\Throwable\Contract\Throwable;
use Valkyrja\Log\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Log\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(Throwable::class, ValkyrjaThrowable::class, true));
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(PhpThrowable::class, $exception);
        self::assertInstanceOf(PhpInvalidArgumentException::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid log level provided';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCode(): void
    {
        $code      = 400;
        $exception = new InvalidArgumentException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid log level');

        throw new InvalidArgumentException('Invalid log level');
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new RuntimeException('Runtime error');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(PhpThrowable::class, $exception);
        self::assertInstanceOf(PhpRuntimeException::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'Failed to write to log file';
        $exception = new RuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new RuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Log operation failed');

        throw new RuntimeException('Log operation failed');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new RuntimeException('Log error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
