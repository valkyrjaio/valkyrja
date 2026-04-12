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

namespace Valkyrja\Tests\Unit\Cache\Throwable;

use RuntimeException;
use Throwable;
use Valkyrja\Cache\Throwable\Contract\CacheThrowable;
use Valkyrja\Cache\Throwable\Exception\CacheInvalidArgumentException;
use Valkyrja\Cache\Throwable\Exception\CacheRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(CacheThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new CacheRuntimeException('Runtime error');

        self::assertInstanceOf(CacheThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new CacheRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new CacheRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(CacheRuntimeException::class);
        $this->expectExceptionMessage('Cache operation failed');

        throw new CacheRuntimeException('Cache operation failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new CacheInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(CacheThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid cache key format';
        $exception = new CacheInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(CacheInvalidArgumentException::class);
        $this->expectExceptionMessage('Cache key is invalid');

        throw new CacheInvalidArgumentException('Cache key is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(CacheRuntimeException::class, CacheThrowable::class, true));
        self::assertTrue(is_a(CacheInvalidArgumentException::class, CacheThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new CacheRuntimeException('Cache error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
