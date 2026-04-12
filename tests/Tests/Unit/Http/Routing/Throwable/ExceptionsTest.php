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
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidArgumentException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingRuntimeException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidMethodTypeException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidParameterRegexException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\HttpRuntimeException;
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
        self::isA(HttpRoutingInvalidArgumentException::class, InvalidMethodTypeException::class);
    }

    public function testInvalidParameterRegexException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, InvalidParameterRegexException::class);
    }

    public function testInvalidRouteNameException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, InvalidRouteNameException::class);
    }

    public function testInvalidRouteParameterException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, InvalidRouteParameterException::class);
    }

    public function testInvalidRoutePathException(): void
    {
        self::isA(HttpRoutingInvalidArgumentException::class, InvalidRoutePathException::class);
    }
}
