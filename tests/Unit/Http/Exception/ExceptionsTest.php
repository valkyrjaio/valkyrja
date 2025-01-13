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

namespace Valkyrja\Tests\Unit\Http\Exception;

use Throwable as PHPThrowable;
use Valkyrja\Exception\InvalidArgumentException as ValkyrjaInvalidArgumentException;
use Valkyrja\Exception\RuntimeException as ValkyrjaRuntimeException;
use Valkyrja\Exception\Throwable as ValkyrjaThrowable;
use Valkyrja\Http\Exception\InvalidArgumentException;
use Valkyrja\Http\Exception\RuntimeException;
use Valkyrja\Http\Exception\Throwable;
use Valkyrja\Tests\Unit\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(ValkyrjaThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, RuntimeException::class);
    }
}
