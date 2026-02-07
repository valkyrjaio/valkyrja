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

namespace Valkyrja\Tests\Unit\Mail\Throwable;

use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Mail\Throwable\Contract\Throwable;
use Valkyrja\Mail\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Mail\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(Throwable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new RuntimeException('Runtime error');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(PhpThrowable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new RuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new RuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Mail sending failed');

        throw new RuntimeException('Mail sending failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid email address format';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email address is invalid');

        throw new InvalidArgumentException('Email address is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new RuntimeException('Mail error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
