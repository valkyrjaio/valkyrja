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

namespace Valkyrja\Tests\Unit\Orm\Throwable;

use InvalidArgumentException as PhpInvalidArgumentException;
use RuntimeException as PhpRuntimeException;
use Throwable as PhpThrowable;
use Valkyrja\Orm\Throwable\Contract\Throwable;
use Valkyrja\Orm\Throwable\Exception\EntityNotFoundException;
use Valkyrja\Orm\Throwable\Exception\ExecuteException;
use Valkyrja\Orm\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Orm\Throwable\Exception\InvalidEntityException;
use Valkyrja\Orm\Throwable\Exception\NotFoundException;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Orm\Throwable\Exception\WhereException;
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
        self::assertInstanceOf(PhpRuntimeException::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'Database connection failed';
        $exception = new RuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Runtime error');

        throw new RuntimeException('Runtime error');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException('Invalid argument');

        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(PhpThrowable::class, $exception);
        self::assertInstanceOf(PhpInvalidArgumentException::class, $exception);
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument');

        throw new InvalidArgumentException('Invalid argument');
    }

    public function testNotFoundExceptionExtendsRuntimeException(): void
    {
        $exception = new NotFoundException('Not found');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testNotFoundExceptionCanBeThrown(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Resource not found');

        throw new NotFoundException('Resource not found');
    }

    public function testEntityNotFoundExceptionExtendsNotFoundException(): void
    {
        $exception = new EntityNotFoundException('Entity not found');

        self::assertInstanceOf(NotFoundException::class, $exception);
        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testEntityNotFoundExceptionCanBeThrown(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('User with ID 123 not found');

        throw new EntityNotFoundException('User with ID 123 not found');
    }

    public function testExecuteExceptionExtendsRuntimeException(): void
    {
        $exception = new ExecuteException('Execute failed');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testExecuteExceptionCanBeThrown(): void
    {
        $this->expectException(ExecuteException::class);
        $this->expectExceptionMessage('Query execution failed');

        throw new ExecuteException('Query execution failed');
    }

    public function testInvalidEntityExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new InvalidEntityException('Invalid entity');

        self::assertInstanceOf(InvalidArgumentException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testInvalidEntityExceptionCanBeThrown(): void
    {
        $this->expectException(InvalidEntityException::class);
        $this->expectExceptionMessage('Entity must implement EntityContract');

        throw new InvalidEntityException('Entity must implement EntityContract');
    }

    public function testWhereExceptionExtendsRuntimeException(): void
    {
        $exception = new WhereException('Where error');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testWhereExceptionCanBeThrown(): void
    {
        $this->expectException(WhereException::class);
        $this->expectExceptionMessage('Invalid WHERE clause');

        throw new WhereException('Invalid WHERE clause');
    }

    public function testExceptionHierarchy(): void
    {
        // RuntimeException hierarchy
        self::assertTrue(is_a(RuntimeException::class, Throwable::class, true));
        self::assertTrue(is_a(NotFoundException::class, RuntimeException::class, true));
        self::assertTrue(is_a(EntityNotFoundException::class, NotFoundException::class, true));
        self::assertTrue(is_a(ExecuteException::class, RuntimeException::class, true));
        self::assertTrue(is_a(WhereException::class, RuntimeException::class, true));

        // InvalidArgumentException hierarchy
        self::assertTrue(is_a(InvalidArgumentException::class, Throwable::class, true));
        self::assertTrue(is_a(InvalidEntityException::class, InvalidArgumentException::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new PhpRuntimeException('Previous error');
        $exception = new RuntimeException('ORM error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionCode(): void
    {
        $exception = new RuntimeException('Error', 500);

        self::assertSame(500, $exception->getCode());
    }
}
