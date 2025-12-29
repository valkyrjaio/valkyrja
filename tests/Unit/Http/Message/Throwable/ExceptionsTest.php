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

use Throwable as PHPThrowable;
use Valkyrja\Http\Message\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\NotFoundHttpException;
use Valkyrja\Http\Message\Throwable\Exception\RuntimeException;
use Valkyrja\Http\Throwable\Contract\Throwable as HttpThrowable;
use Valkyrja\Http\Throwable\Exception\InvalidArgumentException as HttpInvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\RuntimeException as HttpRuntimeException;
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
