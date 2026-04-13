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

use RuntimeException;
use Throwable;
use Valkyrja\Mail\Throwable\Contract\MailThrowable;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailInvalidArgumentException;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(MailThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new MailRuntimeException('Runtime error');

        self::assertInstanceOf(MailThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new MailRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCode(): void
    {
        $code      = 500;
        $exception = new MailRuntimeException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(MailRuntimeException::class);
        $this->expectExceptionMessage('Mail sending failed');

        throw new MailRuntimeException('Mail sending failed');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new MailInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(MailThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid email address format';
        $exception = new MailInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(MailInvalidArgumentException::class);
        $this->expectExceptionMessage('Email address is invalid');

        throw new MailInvalidArgumentException('Email address is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(MailRuntimeException::class, MailThrowable::class, true));
        self::assertTrue(is_a(MailInvalidArgumentException::class, MailThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new MailRuntimeException('Mail error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
