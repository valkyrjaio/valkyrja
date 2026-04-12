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

use InvalidArgumentException;
use RuntimeException;
use Throwable;
use Valkyrja\Orm\Throwable\Contract\OrmThrowable;
use Valkyrja\Orm\Throwable\Exception\EntityNotFoundException;
use Valkyrja\Orm\Throwable\Exception\ExecuteException;
use Valkyrja\Orm\Throwable\Exception\InvalidEntityException;
use Valkyrja\Orm\Throwable\Exception\NotFoundException;
use Valkyrja\Orm\Throwable\Exception\OrmInvalidArgumentException;
use Valkyrja\Orm\Throwable\Exception\OrmRuntimeException;
use Valkyrja\Orm\Throwable\Exception\WhereException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(OrmThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testRuntimeExceptionImplementsThrowable(): void
    {
        $exception = new OrmRuntimeException('Runtime error');

        self::assertInstanceOf(OrmThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testRuntimeExceptionMessage(): void
    {
        $message   = 'Database connection failed';
        $exception = new OrmRuntimeException($message);

        self::assertSame($message, $exception->getMessage());
    }

    public function testRuntimeExceptionCanBeThrown(): void
    {
        $this->expectException(OrmRuntimeException::class);
        $this->expectExceptionMessage('Runtime error');

        throw new OrmRuntimeException('Runtime error');
    }

    public function testInvalidArgumentExceptionImplementsThrowable(): void
    {
        $exception = new OrmInvalidArgumentException('Invalid argument');

        self::assertInstanceOf(OrmThrowable::class, $exception);
        self::assertInstanceOf(Throwable::class, $exception);
        self::assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    public function testInvalidArgumentExceptionCanBeThrown(): void
    {
        $this->expectException(OrmInvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument');

        throw new OrmInvalidArgumentException('Invalid argument');
    }

    public function testNotFoundExceptionExtendsRuntimeException(): void
    {
        $exception = new NotFoundException('Not found');

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
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
        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
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

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
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

        self::assertInstanceOf(OrmInvalidArgumentException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
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

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
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
        self::assertTrue(is_a(OrmRuntimeException::class, OrmThrowable::class, true));
        self::assertTrue(is_a(NotFoundException::class, OrmRuntimeException::class, true));
        self::assertTrue(is_a(EntityNotFoundException::class, NotFoundException::class, true));
        self::assertTrue(is_a(ExecuteException::class, OrmRuntimeException::class, true));
        self::assertTrue(is_a(WhereException::class, OrmRuntimeException::class, true));

        // InvalidArgumentException hierarchy
        self::assertTrue(is_a(OrmInvalidArgumentException::class, OrmThrowable::class, true));
        self::assertTrue(is_a(InvalidEntityException::class, OrmInvalidArgumentException::class, true));
    }

    public function testExceptionWithPreviousException(): void
    {
        $previous  = new RuntimeException('Previous error');
        $exception = new OrmRuntimeException('ORM error', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionCode(): void
    {
        $exception = new OrmRuntimeException('Error', 500);

        self::assertSame(500, $exception->getCode());
    }
}
