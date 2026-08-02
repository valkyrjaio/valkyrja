<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Dispatch\Throwable;

use Throwable;
use Valkyrja\Dispatch\Throwable\Contract\DispatchThrowable;
use Valkyrja\Dispatch\Throwable\Exception\Abstract\DispatchInvalidArgumentException;
use Valkyrja\Dispatch\Throwable\Exception\Abstract\DispatchRuntimeException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidClosureException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidDispatchCapabilityException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidFunctionException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidMethodException;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidPropertyException;
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
        self::isA(DispatchInvalidArgumentException::class, DispatchInvalidClosureException::class);
    }

    public function testInvalidDispatchCapabilityException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, DispatchInvalidDispatchCapabilityException::class);
    }

    public function testInvalidFunctionException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, DispatchInvalidFunctionException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, DispatchInvalidMethodException::class);
    }

    public function testInvalidPropertyException(): void
    {
        self::isA(DispatchInvalidArgumentException::class, DispatchInvalidPropertyException::class);
    }
}
