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

use Throwable as PHPThrowable;
use Valkyrja\Cli\Routing\Throwable\Contract\Throwable;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Routing\Throwable\Exception\RuntimeException;
use Valkyrja\Cli\Throwable\Contract\Throwable as CliThrowable;
use Valkyrja\Cli\Throwable\Exception\InvalidArgumentException as CliInvalidArgumentException;
use Valkyrja\Cli\Throwable\Exception\RuntimeException as CliRuntimeException;
use Valkyrja\Tests\Unit\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(CliThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(CliInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(CliRuntimeException::class, RuntimeException::class);
    }
}
