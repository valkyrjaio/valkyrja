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

namespace Valkyrja\Tests\Unit\Dispatch\Throwable;

use Throwable as PHPThrowable;
use Valkyrja\Dispatch\Throwable\Contract\Throwable;
use Valkyrja\Dispatch\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidClosureException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidDispatchCapabilityException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidFunctionException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidMethodException;
use Valkyrja\Dispatch\Throwable\Exception\InvalidPropertyException;
use Valkyrja\Dispatch\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\InvalidArgumentException as ThrowableInvalidArgumentException;
use Valkyrja\Throwable\Exception\RuntimeException as ThrowableRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(ValkyrjaThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(ThrowableInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(ThrowableRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidClosureException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidClosureException::class);
    }

    public function testInvalidDispatchCapabilityException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidDispatchCapabilityException::class);
    }

    public function testInvalidFunctionException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidFunctionException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidMethodException::class);
    }

    public function testInvalidPropertyException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidPropertyException::class);
    }
}
