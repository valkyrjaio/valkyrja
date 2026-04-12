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

namespace Valkyrja\Tests\Unit\Crypt\Throwable;

use RuntimeException;
use Throwable;
use Valkyrja\Crypt\Throwable\Contract\CryptThrowable;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Crypt\Throwable\Exception\CryptInvalidArgumentException;
use Valkyrja\Crypt\Throwable\Exception\CryptRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(CryptThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testCryptExceptionImplementsThrowable(): void
    {
        $exception = new CryptException('Crypt error');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testCryptExceptionMessage(): void
    {
        $message   = 'Encryption failed';
        $exception = new CryptException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testCryptExceptionCode(): void
    {
        $code      = 500;
        $exception = new CryptException('Error', $code);

        self::assertSame($code, $exception->getCode());
    }

    public function testCryptExceptionCanBeThrown(): void
    {
        $this->expectException(CryptException::class);
        $this->expectExceptionMessage('Decryption failed');

        throw new CryptException('Decryption failed');
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new CryptRuntimeException('Runtime error');

        self::assertInstanceOf(CryptThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'A runtime error occurred';
        $exception = new CryptRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(CryptRuntimeException::class);
        $this->expectExceptionMessage('Runtime crypt error');

        throw new CryptRuntimeException('Runtime crypt error');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new CryptInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(CryptThrowable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid key format';
        $exception = new CryptInvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(CryptInvalidArgumentException::class);
        $this->expectExceptionMessage('Key is invalid');

        throw new CryptInvalidArgumentException('Key is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(CryptRuntimeException::class, CryptThrowable::class, true));
        self::assertTrue(is_a(CryptInvalidArgumentException::class, CryptThrowable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new CryptException('Crypt error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
