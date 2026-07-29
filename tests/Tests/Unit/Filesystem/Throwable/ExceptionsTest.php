<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Filesystem\Throwable;

use ReflectionClass;
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
        self::assertTrue(new ReflectionClass(FilesystemThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
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
        self::assertTrue(new ReflectionClass(FilesystemRuntimeException::class)->isSubclassOf(FilesystemThrowable::class));
        self::assertTrue(new ReflectionClass(FilesystemInvalidArgumentException::class)->isSubclassOf(FilesystemThrowable::class));
        self::assertTrue(new ReflectionClass(FilesystemUnableToReadContentsException::class)->isSubclassOf(FilesystemRuntimeException::class));
    }
}
