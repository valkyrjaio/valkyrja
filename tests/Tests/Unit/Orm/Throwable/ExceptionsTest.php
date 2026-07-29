<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Throwable;

use ReflectionClass;
use Valkyrja\Orm\Throwable\Contract\OrmThrowable;
use Valkyrja\Orm\Throwable\Exception\Abstract\OrmInvalidArgumentException;
use Valkyrja\Orm\Throwable\Exception\Abstract\OrmRuntimeException;
use Valkyrja\Orm\Throwable\Exception\OrmEntityNotFoundException;
use Valkyrja\Orm\Throwable\Exception\OrmExecuteException;
use Valkyrja\Orm\Throwable\Exception\OrmInvalidEntityException;
use Valkyrja\Orm\Throwable\Exception\OrmNotFoundException;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;
use Valkyrja\Orm\Throwable\Exception\OrmWhereException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(OrmThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
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

    public function testUnregisteredEntityExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new OrmUnregisteredEntityException('Unregistered entity');

        self::assertInstanceOf(OrmInvalidArgumentException::class, $exception);
        self::assertInstanceOf(OrmThrowable::class, $exception);
    }

    public function testUnregisteredEntityExceptionCanBeThrown(): void
    {
        $this->expectException(OrmUnregisteredEntityException::class);
        $this->expectExceptionMessage('Entity has no registered metadata');

        throw new OrmUnregisteredEntityException('Entity has no registered metadata');
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
        self::assertTrue(new ReflectionClass(OrmRuntimeException::class)->isSubclassOf(OrmThrowable::class));
        self::assertTrue(new ReflectionClass(OrmNotFoundException::class)->isSubclassOf(OrmRuntimeException::class));
        self::assertTrue(new ReflectionClass(OrmEntityNotFoundException::class)->isSubclassOf(OrmNotFoundException::class));
        self::assertTrue(new ReflectionClass(OrmExecuteException::class)->isSubclassOf(OrmRuntimeException::class));
        self::assertTrue(new ReflectionClass(OrmWhereException::class)->isSubclassOf(OrmRuntimeException::class));

        // InvalidArgumentException hierarchy
        self::assertTrue(new ReflectionClass(OrmInvalidArgumentException::class)->isSubclassOf(OrmThrowable::class));
        self::assertTrue(new ReflectionClass(OrmInvalidEntityException::class)->isSubclassOf(OrmInvalidArgumentException::class));
        self::assertTrue(new ReflectionClass(OrmUnregisteredEntityException::class)->isSubclassOf(OrmInvalidArgumentException::class));
    }
}
