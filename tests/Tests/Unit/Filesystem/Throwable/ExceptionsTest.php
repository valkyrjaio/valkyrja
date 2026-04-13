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

use Valkyrja\Filesystem\Throwable\Contract\FilesystemThrowable;
use Valkyrja\Filesystem\Throwable\Exception\Abstract\FilesystemInvalidArgumentException;
use Valkyrja\Filesystem\Throwable\Exception\Abstract\FilesystemRuntimeException;
use Valkyrja\Filesystem\Throwable\Exception\FilesystemUnableToReadContentsException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(FilesystemThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testUnableToReadContentsExceptionExtendsRuntimeException(): void
    {
        $exception = new FilesystemUnableToReadContentsException('Unable to read');

        self::assertInstanceOf(FilesystemRuntimeException::class, $exception);
        self::assertInstanceOf(FilesystemThrowable::class, $exception);
    }

    public function testUnableToReadContentsExceptionMessage(): void
    {
        $message   = 'Unable to read file contents';
        $exception = new FilesystemUnableToReadContentsException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testUnableToReadContentsExceptionCanBeThrown(): void
    {
        $this->expectException(FilesystemUnableToReadContentsException::class);
        $this->expectExceptionMessage('File not found');

        throw new FilesystemUnableToReadContentsException('File not found');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(FilesystemRuntimeException::class, FilesystemThrowable::class, true));
        self::assertTrue(is_a(FilesystemInvalidArgumentException::class, FilesystemThrowable::class, true));
        self::assertTrue(is_a(FilesystemUnableToReadContentsException::class, FilesystemRuntimeException::class, true));
    }
}
