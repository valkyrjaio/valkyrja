<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Throwable;

use Throwable;
use Valkyrja\Cli\Routing\Throwable\Contract\CliRoutingThrowable;
use Valkyrja\Cli\Routing\Throwable\Exception\Abstract\CliRoutingInvalidArgumentException;
use Valkyrja\Cli\Routing\Throwable\Exception\Abstract\CliRoutingRuntimeException;
use Valkyrja\Cli\Throwable\Contract\CliThrowable;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliInvalidArgumentException;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, CliRoutingThrowable::class);
        self::isA(CliThrowable::class, CliRoutingThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(CliRoutingThrowable::class, CliRoutingInvalidArgumentException::class);
        self::isA(CliInvalidArgumentException::class, CliRoutingInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(CliRoutingThrowable::class, CliRoutingRuntimeException::class);
        self::isA(CliRuntimeException::class, CliRoutingRuntimeException::class);
    }
}
