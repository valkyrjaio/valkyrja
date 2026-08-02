<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Validation\Rule\Orm;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\Orm\EntityExists;
use Valkyrja\Validation\Throwable\Exception\ValidationRuleFailureException;

final class EntityExistsTest extends TestCase
{
    protected MockObject&ManagerContract $orm;

    protected MockObject&RepositoryContract $repository;

    protected function setUp(): void
    {
        $this->orm        = $this->createMock(ManagerContract::class);
        $this->repository = $this->createMock(RepositoryContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->orm->expects($this->never())->method('createRepository');
        $this->repository->expects($this->never())->method('findBy');
        $rule = new EntityExists($this->orm, subject: 1, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $this->orm->expects($this->never())->method('createRepository');
        $this->repository->expects($this->never())->method('findBy');
        $rule = new EntityExists($this->orm, subject: 42, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        self::assertSame(42, $rule->getSubject());
    }

    public function testIsValidWhenEntityExists(): void
    {
        $entity = self::createStub(EntityContract::class);

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn($entity);

        $rule = new EntityExists($this->orm, subject: 1, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWhenEntityDoesNotExist(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null);

        $rule = new EntityExists($this->orm, subject: 1, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        self::assertFalse($rule->isValid());
    }

    public function testIsValidWithStringSubject(): void
    {
        $entity = self::createStub(EntityContract::class);

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn($entity);

        $rule = new EntityExists($this->orm, subject: 'string-id', errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        self::assertTrue($rule->isValid());
    }

    public function testValidatePassesWhenEntityExists(): void
    {
        $entity = self::createStub(EntityContract::class);

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn($entity);

        $rule = new EntityExists($this->orm, subject: 1, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWhenEntityDoesNotExist(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null);

        $rule = new EntityExists($this->orm, subject: 999, errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class);

        $this->expectException(ValidationRuleFailureException::class);
        $this->expectExceptionMessage(ErrorMessage::ENTITY_EXISTS);

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null);

        $rule = new EntityExists($this->orm, subject: 999, errorMessage: 'User not found', entity: EntityFixture::class);

        $this->expectException(ValidationRuleFailureException::class);
        $this->expectExceptionMessage('User not found');

        $rule->validate();
    }

    public function testWithCustomField(): void
    {
        $entity = self::createStub(EntityContract::class);

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->with(self::callback(static function (Where $where): bool {
                $value = $where->value;

                return $value instanceof Value
                    && $value->name === 'email'
                    && $value->value === 'test@example.com';
            }))
            ->willReturn($entity);

        $rule = new EntityExists($this->orm, subject: 'test@example.com', errorMessage: ErrorMessage::ENTITY_EXISTS, entity: EntityFixture::class, field: 'email');

        self::assertTrue($rule->isValid());
    }
}
