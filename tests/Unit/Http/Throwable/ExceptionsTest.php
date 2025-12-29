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

namespace Valkyrja\Tests\Unit\Http\Throwable;

use Throwable as PHPThrowable;
use Valkyrja\Http\Throwable\Contract\Throwable;
use Valkyrja\Http\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\InvalidArgumentException as ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\RuntimeException as ValkyrjaRuntimeException;

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
