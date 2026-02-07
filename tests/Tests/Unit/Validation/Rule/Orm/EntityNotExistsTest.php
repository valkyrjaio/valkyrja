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

namespace Valkyrja\Tests\Unit\Validation\Rule\Orm;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Tests\Classes\Orm\Entity\EntityClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Rule\Orm\EntityNotExists;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

final class EntityNotExistsTest extends TestCase
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
        $rule = new EntityNotExists($this->orm, 1, EntityClass::class);

        self::assertInstanceOf(RuleContract::class, $rule);
    }

    public function testGetSubject(): void
    {
        $this->orm->expects($this->never())->method('createRepository');
        $this->repository->expects($this->never())->method('findBy');
        $rule = new EntityNotExists($this->orm, 42, EntityClass::class);

        self::assertSame(42, $rule->getSubject());
    }

    public function testIsValidWhenEntityDoesNotExist(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null);

        $rule = new EntityNotExists($this->orm, 1, EntityClass::class);

        self::assertTrue($rule->isValid());
    }

    public function testIsInvalidWhenEntityExists(): void
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

        $rule = new EntityNotExists($this->orm, 1, EntityClass::class);

        self::assertFalse($rule->isValid());
    }

    public function testValidatePassesWhenEntityDoesNotExist(): void
    {
        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->willReturn($this->repository);
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn(null);

        $rule = new EntityNotExists($this->orm, 'new-email@example.com', EntityClass::class);

        // Should not throw
        $rule->validate();

        self::assertTrue(true);
    }

    public function testValidateThrowsWhenEntityExists(): void
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

        $rule = new EntityNotExists($this->orm, 1, EntityClass::class);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The entity does exist');

        $rule->validate();
    }

    public function testCustomErrorMessage(): void
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

        $rule = new EntityNotExists($this->orm, 'taken@example.com', EntityClass::class, null, 'Email already in use');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Email already in use');

        $rule->validate();
    }

    public function testWithCustomField(): void
    {
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
                    && $value->name === 'username'
                    && $value->value === 'newuser';
            }))
            ->willReturn(null);

        $rule = new EntityNotExists($this->orm, 'newuser', EntityClass::class, 'username');

        self::assertTrue($rule->isValid());
    }
}
