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

namespace Valkyrja\Tests\Unit\Http\Message\File\Throwable;

use Throwable;
use Valkyrja\Http\Message\File\Throwable\Contract\UploadedFileThrowable;
use Valkyrja\Http\Message\File\Throwable\Exception\Abstract\UploadedFileInvalidArgumentException;
use Valkyrja\Http\Message\File\Throwable\Exception\Abstract\UploadedFileRuntimeException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileAlreadyMovedException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidDirectoryException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidUploadedFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileMoveFailureException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUnableToWriteFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileUploadErrorException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, UploadedFileThrowable::class);
        self::isA(HttpMessageThrowable::class, UploadedFileThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(UploadedFileThrowable::class, UploadedFileInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, UploadedFileInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(UploadedFileThrowable::class, UploadedFileRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, UploadedFileRuntimeException::class);
    }

    public function testAlreadyMovedException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UploadedFileAlreadyMovedException::class);
    }

    public function testInvalidDirectoryException(): void
    {
        self::isA(UploadedFileInvalidArgumentException::class, UploadedFileInvalidDirectoryException::class);
    }

    public function testInvalidUploadedFileException(): void
    {
        self::isA(UploadedFileInvalidArgumentException::class, UploadedFileInvalidUploadedFileException::class);
    }

    public function testMoveFailureException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UploadedFileMoveFailureException::class);
    }

    public function testUploadedFileException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UploadedFileUnableToWriteFileException::class);
    }

    public function testUploadErrorException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UploadedFileUploadErrorException::class);
    }
}
