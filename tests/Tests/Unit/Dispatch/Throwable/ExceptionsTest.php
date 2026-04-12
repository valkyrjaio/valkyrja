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

namespace Valkyrja\Tests\Unit\Dispatch\Throwable;

use Throwable;
use Valkyrja\Dispatch\Throwable\Contract\DispatchThrowable;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidArgumentException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchRuntimeException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidClosureException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidDispatchCapabilityException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidFunctionException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidMethodException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidPropertyException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, DispatchThrowable::class);
        self::isA(ValkyrjaThrowable::class, DispatchThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(DispatchThrowable::class, DispatchInvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, DispatchInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(DispatchThrowable::class, DispatchRuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, DispatchRuntimeException::class);
    }

    public function testInvalidClosureException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, InvalidClosureException::class);
    }

    public function testInvalidDispatchCapabilityException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, InvalidDispatchCapabilityException::class);
    }

    public function testInvalidFunctionException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, InvalidFunctionException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, InvalidMethodException::class);
    }

    public function testInvalidPropertyException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, InvalidPropertyException::class);
    }
}
