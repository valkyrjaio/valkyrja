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

namespace Valkyrja\Tests\Unit\Http\Middleware\Throwable;

use Throwable as PHPThrowable;
use Valkyrja\Http\Middleware\Throwable\Contract\Throwable;
use Valkyrja\Http\Middleware\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Middleware\Throwable\Exception\RuntimeException;
use Valkyrja\Http\Throwable\Contract\Throwable as HttpThrowable;
use Valkyrja\Http\Throwable\Exception\InvalidArgumentException as HttpInvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\RuntimeException as HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(HttpThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(HttpInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(HttpRuntimeException::class, RuntimeException::class);
    }
}
