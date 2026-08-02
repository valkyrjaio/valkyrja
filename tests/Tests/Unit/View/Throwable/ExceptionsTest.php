<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Throwable;

use Throwable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;
use Valkyrja\View\Throwable\Contract\ViewThrowable;
use Valkyrja\View\Throwable\Exception\Abstract\ViewInvalidArgumentException;
use Valkyrja\View\Throwable\Exception\Abstract\ViewRuntimeException;
use Valkyrja\View\Throwable\Exception\ViewInvalidPathException;

/**
 * Test the View exceptions.
 */
final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, ViewThrowable::class);
        self::isA(ValkyrjaThrowable::class, ViewThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(ViewThrowable::class, ViewInvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, ViewInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(ViewThrowable::class, ViewRuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, ViewRuntimeException::class);
    }

    public function testInvalidConfigPath(): void
    {
        self::isA(ViewThrowable::class, ViewInvalidPathException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, ViewInvalidPathException::class);
    }
}
