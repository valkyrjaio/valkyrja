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

namespace Valkyrja\Tests\Unit\Http\Message\Exception;

use Throwable as PHPThrowable;
use Valkyrja\Http\Exception\InvalidArgumentException as HttpInvalidArgumentException;
use Valkyrja\Http\Exception\RuntimeException as HttpRuntimeException;
use Valkyrja\Http\Exception\Throwable as HttpThrowable;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Exception\NotFoundHttpException;
use Valkyrja\Http\Message\Exception\RuntimeException;
use Valkyrja\Http\Message\Exception\Throwable;
use Valkyrja\Tests\Unit\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(HttpThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(HttpInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(HttpRuntimeException::class, RuntimeException::class);
    }

    public function testHttpException(): void
    {
        self::isA(RuntimeException::class, HttpException::class);
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
