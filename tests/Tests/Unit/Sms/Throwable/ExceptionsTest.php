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

use RuntimeException;
use Throwable;
use Valkyrja\Sms\Throwable\Contract\SmsThrowable;
use Valkyrja\Sms\Throwable\Exception\SmsInvalidArgumentException;
use Valkyrja\Sms\Throwable\Exception\SmsRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(SmsThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new SmsRuntimeException('Runtime error');

        self::assertInstanceOf(SmsThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new SmsRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new SmsRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(SmsRuntimeException::class);
        $this->expectExceptionMessage('SMS sending failed');

        throw new SmsRuntimeException('SMS sending failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new SmsInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(SmsThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid phone number format';
        $exception = new SmsInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(SmsInvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number is invalid');

        throw new SmsInvalidArgumentException('Phone number is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(SmsRuntimeException::class, SmsThrowable::class, true));
        self::assertTrue(is_a(SmsInvalidArgumentException::class, SmsThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new SmsRuntimeException('SMS error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
