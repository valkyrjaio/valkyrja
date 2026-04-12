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
use Valkyrja\Http\Message\File\Throwable\Exception\AlreadyMovedException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidDirectoryException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidUploadedFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\MoveFailureException;
use Valkyrja\Http\Message\File\Throwable\Exception\UnableToWriteFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidArgumentException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileRuntimeException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadErrorException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
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
        self::isA(UploadedFileRuntimeException::class, AlreadyMovedException::class);
    }

    public function testInvalidDirectoryException(): void
    {
        self::isA(UploadedFileInvalidArgumentException::class, InvalidDirectoryException::class);
    }

    public function testInvalidUploadedFileException(): void
    {
        self::isA(UploadedFileInvalidArgumentException::class, InvalidUploadedFileException::class);
    }

    public function testMoveFailureException(): void
    {
        self::isA(UploadedFileRuntimeException::class, MoveFailureException::class);
    }

    public function testUploadedFileException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UnableToWriteFileException::class);
    }

    public function testUploadErrorException(): void
    {
        self::isA(UploadedFileRuntimeException::class, UploadErrorException::class);
    }
}
