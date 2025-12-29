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

namespace Valkyrja\Tests\Unit\Dispatch\Exception;

use Throwable as PHPThrowable;
use Valkyrja\Dispatch\Exception\InvalidArgumentException;
use Valkyrja\Dispatch\Exception\InvalidClosureException;
use Valkyrja\Dispatch\Exception\InvalidDispatchCapabilityException;
use Valkyrja\Dispatch\Exception\InvalidFunctionException;
use Valkyrja\Dispatch\Exception\InvalidMethodException;
use Valkyrja\Dispatch\Exception\InvalidPropertyException;
use Valkyrja\Dispatch\Exception\RuntimeException;
use Valkyrja\Dispatch\Exception\Throwable;
use Valkyrja\Exception\InvalidArgumentException as ValkyrjaInvalidArgumentException;
use Valkyrja\Exception\RuntimeException as ValkyrjaRuntimeException;
use Valkyrja\Exception\Throwable as ValkyrjaThrowable;
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
