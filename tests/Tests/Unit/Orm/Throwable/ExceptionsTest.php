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

use Valkyrja\Orm\Throwable\Contract\OrmThrowable;
use Valkyrja\Orm\Throwable\Exception\Abstract\OrmInvalidArgumentException;
use Valkyrja\Orm\Throwable\Exception\Abstract\OrmRuntimeException;
use Valkyrja\Orm\Throwable\Exception\OrmEntityNotFoundException;
use Valkyrja\Orm\Throwable\Exception\OrmExecuteException;
use Valkyrja\Orm\Throwable\Exception\OrmInvalidEntityException;
use Valkyrja\Orm\Throwable\Exception\OrmNotFoundException;
use Valkyrja\Orm\Throwable\Exception\OrmWhereException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(OrmThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testNotFoundExceptionExtendsRuntimeException(): void
    {
        $exception = new OrmNotFoundException('Not found');

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testNotFoundExceptionCanBeThrown(): void
    {
        $this->expectException(OrmNotFoundException::class);
        $this->expectExceptionMessage('Resource not found');

        throw new OrmNotFoundException('Resource not found');
    }

    public function testEntityNotFoundExceptionExtendsNotFoundException(): void
    {
        $exception = new OrmEntityNotFoundException('Entity not found');

        self::assertInstanceOf(OrmNotFoundException::class, $exception);
        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testEntityNotFoundExceptionCanBeThrown(): void
    {
        $this->expectException(OrmEntityNotFoundException::class);
        $this->expectExceptionMessage('User with ID 123 not found');

        throw new OrmEntityNotFoundException('User with ID 123 not found');
    }

    public function testExecuteExceptionExtendsRuntimeException(): void
    {
        $exception = new OrmExecuteException('Execute failed');

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testExecuteExceptionCanBeThrown(): void
    {
        $this->expectException(OrmExecuteException::class);
        $this->expectExceptionMessage('Query execution failed');

        throw new OrmExecuteException('Query execution failed');
    }

    public function testInvalidEntityExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new OrmInvalidEntityException('Invalid entity');

        self::assertInstanceOf(OrmInvalidArgumentException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testInvalidEntityExceptionCanBeThrown(): void
    {
        $this->expectException(OrmInvalidEntityException::class);
        $this->expectExceptionMessage('Entity must implement EntityContract');

        throw new OrmInvalidEntityException('Entity must implement EntityContract');
    }

    public function testWhereExceptionExtendsRuntimeException(): void
    {
        $exception = new OrmWhereException('Where error');

        self::assertInstanceOf(OrmRuntimeException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testWhereExceptionCanBeThrown(): void
    {
        $this->expectException(OrmWhereException::class);
        $this->expectExceptionMessage('Invalid WHERE clause');

        throw new OrmWhereException('Invalid WHERE clause');
    }

    public function testExceptionHierarchy(): void
    {
        // RuntimeException hierarchy
        self::assertTrue(is_a(OrmRuntimeException::class, OrmThrowable::class, true));
        self::assertTrue(is_a(OrmNotFoundException::class, OrmRuntimeException::class, true));
        self::assertTrue(is_a(OrmEntityNotFoundException::class, OrmNotFoundException::class, true));
        self::assertTrue(is_a(OrmExecuteException::class, OrmRuntimeException::class, true));
        self::assertTrue(is_a(OrmWhereException::class, OrmRuntimeException::class, true));

        // InvalidArgumentException hierarchy
        self::assertTrue(is_a(OrmInvalidArgumentException::class, OrmThrowable::class, true));
        self::assertTrue(is_a(OrmInvalidEntityException::class, OrmInvalidArgumentException::class, true));
    }
}
