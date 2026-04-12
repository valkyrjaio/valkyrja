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

use RuntimeException;
use Throwable;
use Valkyrja\Filesystem\Throwable\Contract\FilesystemThrowable;
use Valkyrja\Filesystem\Throwable\Exception\FilesystemInvalidArgumentException;
use Valkyrja\Filesystem\Throwable\Exception\FilesystemRuntimeException;
use Valkyrja\Filesystem\Throwable\Exception\UnableToReadContentsException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(FilesystemThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new FilesystemRuntimeException('Runtime error');

        self::assertInstanceOf(FilesystemThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new FilesystemRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new FilesystemRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(FilesystemRuntimeException::class);
        $this->expectExceptionMessage('Filesystem operation failed');

        throw new FilesystemRuntimeException('Filesystem operation failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new FilesystemInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(FilesystemThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid path format';
        $exception = new FilesystemInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(FilesystemInvalidArgumentException::class);
        $this->expectExceptionMessage('Path is invalid');

        throw new FilesystemInvalidArgumentException('Path is invalid');
    }

    public function testUnableToReadContentsExceptionExtendsRuntimeException(): void
    {
        $exception = new UnableToReadContentsException('Unable to read');

        self::assertInstanceOf(FilesystemRuntimeException::class, $exception);
        self::assertInstanceOf(FilesystemThrowable::class, $exception);
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
        self::assertTrue(is_a(FilesystemRuntimeException::class, FilesystemThrowable::class, true));
        self::assertTrue(is_a(FilesystemInvalidArgumentException::class, FilesystemThrowable::class, true));
        self::assertTrue(is_a(UnableToReadContentsException::class, FilesystemRuntimeException::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new FilesystemRuntimeException('Filesystem error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
