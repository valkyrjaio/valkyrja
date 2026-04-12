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

namespace Valkyrja\Tests\Unit\Http\Message\Throwable;

use Throwable;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Throwable\Exception\NotFoundHttpException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpMessageThrowable::class);
        self::isA(HttpThrowable::class, HttpMessageThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpMessageThrowable::class, HttpMessageInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, HttpMessageInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpMessageThrowable::class, HttpMessageRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpMessageRuntimeException::class);
    }

    public function testHttpException(): void
    {
        self::isA(HttpRuntimeException::class, HttpException::class);
    }

    public function testHttpRedirectException(): void
    {
        self::isA(HttpException::class, HttpRedirectException::class);
    }

    public function testNotFoundHttpException(): void
    {
        self::isA(HttpException::class, NotFoundHttpException::class);
    }
}
