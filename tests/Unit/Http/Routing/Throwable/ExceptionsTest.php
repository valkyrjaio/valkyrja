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

use Throwable as PHPThrowable;
use Valkyrja\Http\Routing\Throwable\Contract\Throwable;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidMethodTypeException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidParameterRegexException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Throwable\Exception\RuntimeException;
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

    public function testInvalidMethodTypeException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidMethodTypeException::class);
    }

    public function testInvalidParameterRegexException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidParameterRegexException::class);
    }

    public function testInvalidRouteNameException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidRouteNameException::class);
    }

    public function testInvalidRouteParameterException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidRouteParameterException::class);
    }

    public function testInvalidRoutePathException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidRoutePathException::class);
    }
}
