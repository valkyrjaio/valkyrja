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

namespace Valkyrja\Tests\Unit\Broadcast\Throwable;

use RuntimeException;
use Throwable;
use Valkyrja\Broadcast\Throwable\Contract\BroadcastThrowable;
use Valkyrja\Broadcast\Throwable\Exception\BroadcastInvalidArgumentException;
use Valkyrja\Broadcast\Throwable\Exception\BroadcastRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(BroadcastThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new BroadcastRuntimeException('Runtime error');

        self::assertInstanceOf(BroadcastThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new BroadcastRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new BroadcastRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(BroadcastRuntimeException::class);
        $this->expectExceptionMessage('Broadcast failed');

        throw new BroadcastRuntimeException('Broadcast failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new BroadcastInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(BroadcastThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid channel format';
        $exception = new BroadcastInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(BroadcastInvalidArgumentException::class);
        $this->expectExceptionMessage('Channel is invalid');

        throw new BroadcastInvalidArgumentException('Channel is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(BroadcastRuntimeException::class, BroadcastThrowable::class, true));
        self::assertTrue(is_a(BroadcastInvalidArgumentException::class, BroadcastThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new BroadcastRuntimeException('Broadcast error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
