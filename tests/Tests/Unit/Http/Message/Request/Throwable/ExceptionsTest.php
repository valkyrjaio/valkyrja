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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Throwable;

use Throwable;
use Valkyrja\Http\Message\Request\Throwable\Contract\RequestThrowable;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidMethodException;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidRequestTargetException;
use Valkyrja\Http\Message\Request\Throwable\Exception\RequestInvalidArgumentException;
use Valkyrja\Http\Message\Request\Throwable\Exception\RequestRuntimeException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, RequestThrowable::class);
        self::isA(HttpMessageThrowable::class, RequestThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(RequestThrowable::class, RequestInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, RequestInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(RequestThrowable::class, RequestRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, RequestRuntimeException::class);
    }

    public function testInvalidRequestTargetException(): void
    {
        self::isA(RequestInvalidArgumentException::class, InvalidRequestTargetException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(RequestInvalidArgumentException::class, InvalidMethodException::class);
    }
}
