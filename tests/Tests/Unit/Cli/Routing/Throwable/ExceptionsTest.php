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

namespace Valkyrja\Tests\Unit\Cli\Routing\Throwable;

use Throwable;
use Valkyrja\Cli\Routing\Throwable\Contract\CliRoutingThrowable;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidArgumentException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingRuntimeException;
use Valkyrja\Cli\Throwable\Contract\CliThrowable;
use Valkyrja\Cli\Throwable\Exception\CliInvalidArgumentException;
use Valkyrja\Cli\Throwable\Exception\CliRuntimeException;
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
