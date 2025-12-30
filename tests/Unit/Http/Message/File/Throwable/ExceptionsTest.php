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

use Throwable as PHPThrowable;
use Valkyrja\Http\Message\File\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\File\Throwable\Exception\AlreadyMovedException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidDirectoryException;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidUploadedFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\MoveFailureException;
use Valkyrja\Http\Message\File\Throwable\Exception\RuntimeException;
use Valkyrja\Http\Message\File\Throwable\Exception\UnableToWriteFileException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadErrorException;
use Valkyrja\Http\Message\Throwable\Contract\Throwable as MessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException as MessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\RuntimeException as MessageRuntimeException;
use Valkyrja\Tests\Unit\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(MessageThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(MessageInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(MessageRuntimeException::class, RuntimeException::class);
    }

    public function testAlreadyMovedException(): void
    {
        self::isA(RuntimeException::class, AlreadyMovedException::class);
    }

    public function testInvalidDirectoryException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidDirectoryException::class);
    }

    public function testInvalidUploadedFileException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidUploadedFileException::class);
    }

    public function testMoveFailureException(): void
    {
        self::isA(RuntimeException::class, MoveFailureException::class);
    }

    public function testUploadedFileException(): void
    {
        self::isA(RuntimeException::class, UnableToWriteFileException::class);
    }

    public function testUploadErrorException(): void
    {
        self::isA(RuntimeException::class, UploadErrorException::class);
    }
}
