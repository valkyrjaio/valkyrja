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

namespace Valkyrja\Tests\Unit\Http\Middleware\Throwable;

use Throwable;
use Valkyrja\Http\Middleware\Throwable\Contract\HttpMiddlewareThrowable;
use Valkyrja\Http\Middleware\Throwable\Exception\HttpMiddlewareInvalidArgumentException;
use Valkyrja\Http\Middleware\Throwable\Exception\HttpMiddlewareRuntimeException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpMiddlewareThrowable::class);
        self::isA(HttpThrowable::class, HttpMiddlewareThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpMiddlewareThrowable::class, HttpMiddlewareInvalidArgumentException::class);
        self::isA(HttpMiddlewareInvalidArgumentException::class, HttpMiddlewareInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpMiddlewareThrowable::class, HttpMiddlewareRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpMiddlewareRuntimeException::class);
    }
}
