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

namespace Valkyrja\Tests\Unit\Sms\Throwable;

use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Sms\Throwable\Contract\Throwable;
use Valkyrja\Sms\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Sms\Throwable\Exception\RuntimeException;
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
        $this->expectExceptionMessage('SMS sending failed');

        throw new RuntimeException('SMS sending failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid phone number format';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number is invalid');

        throw new InvalidArgumentException('Phone number is invalid');
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
        $exception = new RuntimeException('SMS error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
