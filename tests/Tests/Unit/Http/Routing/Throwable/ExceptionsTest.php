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

namespace Valkyrja\Tests\Unit\Http\Routing\Throwable;

use Throwable;
use Valkyrja\Http\Routing\Throwable\Contract\HttpRoutingThrowable;
use Valkyrja\Http\Routing\Throwable\Exception\Abstract\HttpRoutingInvalidArgumentException;
use Valkyrja\Http\Routing\Throwable\Exception\Abstract\HttpRoutingRuntimeException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidMethodTypeException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidParameterRegexException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteParameterException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRoutePathException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpRoutingThrowable::class);
        self::isA(HttpThrowable::class, HttpRoutingThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpRoutingThrowable::class, HttpRoutingInvalidArgumentException::class);
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpRoutingThrowable::class, HttpRoutingRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpRoutingRuntimeException::class);
    }

    public function testInvalidMethodTypeException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidMethodTypeException::class);
    }

    public function testInvalidParameterRegexException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidParameterRegexException::class);
    }

    public function testInvalidRouteNameException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidRouteNameException::class);
    }

    public function testInvalidRouteParameterException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidRouteParameterException::class);
    }

    public function testInvalidRoutePathException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, HttpRoutingInvalidRoutePathException::class);
    }
}
