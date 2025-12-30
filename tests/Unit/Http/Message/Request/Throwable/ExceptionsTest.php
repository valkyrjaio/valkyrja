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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Throwable;

use Throwable as PHPThrowable;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidMethodException;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidRequestTargetException;
use Valkyrja\Http\Message\Request\Throwable\Exception\RuntimeException;
use Valkyrja\Http\Message\Request\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\Throwable\Contract\Throwable as MessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException as MessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\RuntimeException as MessageRuntimeException;
use Valkyrja\Tests\Unit\TestCase;

class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(MessageThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(MessageInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(MessageRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidRequestTargetException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidRequestTargetException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidMethodException::class);
    }
}
