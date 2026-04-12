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

namespace Valkyrja\Tests\Unit\Jwt\Throwable;

use RuntimeException;
use Throwable;
use Valkyrja\Jwt\Throwable\Contract\JwtThrowable;
use Valkyrja\Jwt\Throwable\Exception\JwtInvalidArgumentException;
use Valkyrja\Jwt\Throwable\Exception\JwtRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(JwtThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new JwtRuntimeException('Runtime error');

        self::assertInstanceOf(JwtThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new JwtRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new JwtRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(JwtRuntimeException::class);
        $this->expectExceptionMessage('JWT encoding failed');

        throw new JwtRuntimeException('JWT encoding failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new JwtInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(JwtThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid token format';
        $exception = new JwtInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(JwtInvalidArgumentException::class);
        $this->expectExceptionMessage('Token is invalid');

        throw new JwtInvalidArgumentException('Token is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(JwtRuntimeException::class, JwtThrowable::class, true));
        self::assertTrue(is_a(JwtInvalidArgumentException::class, JwtThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new JwtRuntimeException('JWT error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
