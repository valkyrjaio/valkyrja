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

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Throwable;

use Throwable;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable as MessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
use Valkyrja\Http\Message\Uri\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpMessageThrowable::class);
        self::isA(MessageThrowable::class, HttpMessageThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpMessageThrowable::class, InvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpMessageThrowable::class, RuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidDirectoryException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidPathException::class);
    }

    public function testInvalidPortException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidPortException::class);
    }

    public function testInvalidQueryException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidQueryException::class);
    }
}
