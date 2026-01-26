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

use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Crypt\Throwable\Contract\Throwable;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Crypt\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Crypt\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as ValkyrjaThrowable;

class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(Throwable::class, ValkyrjaThrowable::class, true));
    }

    public function testCryptExceptionImplementsThrowable(): void
    {
        $exception = new CryptException('Crypt error');

        self::assertInstanceOf(PhpThrowable::class, $exception);
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

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Runtime crypt error');

        throw new RuntimeException('Runtime crypt error');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message   = 'Invalid key format';
        $exception = new InvalidArgumentException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key is invalid');

        throw new InvalidArgumentException('Key is invalid');
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new CryptException('Crypt error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }
}
