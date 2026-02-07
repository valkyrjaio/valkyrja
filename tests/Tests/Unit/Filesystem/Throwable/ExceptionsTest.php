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

namespace Valkyrja\Tests\Unit\Filesystem\Throwable;

use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Filesystem\Throwable\Contract\Throwable;
use Valkyrja\Filesystem\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Filesystem\Throwable\Exception\RuntimeException;
use Valkyrja\Filesystem\Throwable\Exception\UnableToReadContentsException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;

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

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Filesystem operation failed');

        throw new RuntimeException('Filesystem operation failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid path format';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Path is invalid');

        throw new InvalidArgumentException('Path is invalid');
    }

    public function testUnableToReadContentsExceptionExtendsRuntimeException(): void
    {
        $exception = new UnableToReadContentsException('Unable to read');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testUnableToReadContentsExceptionMessage(): void
    {
        $message   = 'Unable to read file contents';
        $exception = new UnableToReadContentsException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testUnableToReadContentsExceptionCanBeThrown(): void
    {
        $this->expectException(UnableToReadContentsException::class);
        $this->expectExceptionMessage('File not found');

        throw new UnableToReadContentsException('File not found');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
        self::assertTrue(is_a(UnableToReadContentsException::class, RuntimeException::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new RuntimeException('Filesystem error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
