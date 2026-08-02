<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Middleware\Throwable;

use Throwable;
use Valkyrja\Cli\Middleware\Throwable\Contract\CliMiddlewareThrowable;
use Valkyrja\Cli\Middleware\Throwable\Exception\Abstract\CliMiddlewareInvalidArgumentException;
use Valkyrja\Cli\Middleware\Throwable\Exception\Abstract\CliMiddlewareRuntimeException;
use Valkyrja\Cli\Throwable\Contract\CliThrowable;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliInvalidArgumentException;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, CliMiddlewareThrowable::class);
        self::isA(CliThrowable::class, CliMiddlewareThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(CliMiddlewareThrowable::class, CliMiddlewareInvalidArgumentException::class);
        self::isA(CliInvalidArgumentException::class, CliMiddlewareInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(CliMiddlewareThrowable::class, CliMiddlewareRuntimeException::class);
        self::isA(CliRuntimeException::class, CliMiddlewareRuntimeException::class);
    }
}
