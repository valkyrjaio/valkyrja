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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Throwable;

use Throwable;
use Valkyrja\Cli\Middleware\Throwable\Contract\CliMiddlewareThrowable;
use Valkyrja\Cli\Middleware\Throwable\Exception\CliMiddlewareInvalidArgumentException;
use Valkyrja\Cli\Middleware\Throwable\Exception\CliMiddlewareRuntimeException;
use Valkyrja\Cli\Throwable\Contract\CliThrowable;
use Valkyrja\Cli\Throwable\Exception\CliInvalidArgumentException;
use Valkyrja\Cli\Throwable\Exception\CliRuntimeException;
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
