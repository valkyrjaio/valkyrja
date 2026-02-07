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

namespace Valkyrja\Tests\Unit\Orm\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Tests\Classes\Orm\Entity\EntityIntIdClass;
use Valkyrja\Tests\Classes\Orm\Entity\EntityStringIdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class RepositoryTest extends TestCase
{
    protected ManagerContract&MockObject $manager;

    protected QueryBuilderFactoryContract&MockObject $queryBuilderFactory;

    protected StatementContract&MockObject $statement;

    protected Repository $repository;

    /** @var class-string<EntityContract> */
    protected string $entityClass;

    protected function setUp(): void
    {
        $this->manager             = $this->createMock(ManagerContract::class);
        $this->queryBuilderFactory = $this->createMock(QueryBuilderFactoryContract::class);
        $this->statement           = $this->createMock(StatementContract::class);

        $this->entityClass = EntityIntIdClass::class;

        $this->repository = new Repository($this->manager, $this->entityClass);
    }

    public function testImplementsRepositoryContract(): void
    {
        $this->manager->expects($this->never())->method('createQueryBuilder');
        $this->queryBuilderFactory->expects($this->never())->method('select');
        $this->statement->expects($this->never())->method('fetchAll');

        self::assertInstanceOf(RepositoryContract::class, $this->repository);
    }

    public function testFindReturnsEntityWhenFound(): void
    {
        $selectBuilder = $this->createMock(SelectQueryBuilderContract::class);

        $selectBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('select')
            ->with('test')
            ->willReturn($selectBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $entityData = ['id' => 1, 'name' => 'Test Entity'];
        $entity     = ($this->entityClass)::fromArray($entityData);

        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->with($this->entityClass)
            ->willReturn([$entity]);

        $result = $this->repository->find(1);

        self::assertInstanceOf(EntityContract::class, $result);
        self::assertSame(1, $result->id);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $selectBuilder = $this->createMock(SelectQueryBuilderContract::class);

        $selectBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $result = $this->repository->find(999);

        self::assertNull($result);
    }

    public function testFindByReturnsEntityWhenFound(): void
    {
        $selectBuilder = $this->createMock(SelectQueryBuilderContract::class);

        $selectBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('select')
            ->with('test')
            ->willReturn($selectBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $entityData = ['id' => 1, 'name' => 'Test Entity'];
        $entity     = ($this->entityClass)::fromArray($entityData);

        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$entity]);

        $where  = new Where(new Value('name', 'Test Entity'));
        $result = $this->repository->findBy($where);

        self::assertInstanceOf(EntityContract::class, $result);
    }

    public function testAllReturnsArrayOfEntities(): void
    {
        $selectBuilder = $this->createMock(SelectQueryBuilderContract::class);

        $selectBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $entities = [
            ($this->entityClass)::fromArray(['id' => 1, 'name' => 'Entity 1']),
            ($this->entityClass)::fromArray(['id' => 2, 'name' => 'Entity 2']),
        ];

        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($entities);

        $result = $this->repository->all();

        self::assertCount(2, $result);
        self::assertContainsOnlyInstancesOf(EntityContract::class, $result);
    }

    public function testAllByReturnsFilteredEntities(): void
    {
        $selectBuilder = $this->createMock(SelectQueryBuilderContract::class);

        $selectBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $entities = [
            ($this->entityClass)::fromArray(['id' => 1, 'name' => 'Test']),
        ];

        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($entities);

        $where  = new Where(new Value('name', 'Test'));
        $result = $this->repository->allBy($where);

        self::assertCount(1, $result);
    }

    public function testCreateInsertsEntity(): void
    {
        $insertBuilder = $this->createMock(InsertQueryBuilderContract::class);

        $insertBuilder
            ->expects($this->once())
            ->method('withSet')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('insert')
            ->with('test')
            ->willReturn($insertBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->manager
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('test', 'id')
            ->willReturn('123');

        $this->statement
            ->expects($this->never())
            ->method('execute');

        $entity = ($this->entityClass)::fromArray(['name' => 'New Entity']);

        $this->repository->create($entity);

        self::assertSame(123, $entity->id);
    }

    public function testCreateInsertsEntityWithStringId(): void
    {
        $insertBuilder = $this->createMock(InsertQueryBuilderContract::class);

        $insertBuilder
            ->expects($this->once())
            ->method('withSet')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('insert')
            ->with('test')
            ->willReturn($insertBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->manager
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('test', 'id')
            ->willReturn('123');

        $this->statement
            ->expects($this->never())
            ->method('execute');

        $entity = EntityStringIdClass::fromArray(['name' => 'New Entity']);

        $this->repository->create($entity);

        self::assertSame('123', $entity->id);
    }

    public function testUpdateUpdatesEntity(): void
    {
        $updateBuilder = $this->createMock(UpdateQueryBuilderContract::class);

        $updateBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $updateBuilder
            ->expects($this->once())
            ->method('withSet')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('update')
            ->with('test')
            ->willReturn($updateBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->statement
            ->expects($this->never())
            ->method('execute');

        $entity = ($this->entityClass)::fromArray(['id' => 1, 'name' => 'Updated Entity']);

        $this->repository->update($entity);

        self::assertTrue(true);
    }

    public function testUpdateWithChangedProperties(): void
    {
        $updateBuilder = $this->createMock(UpdateQueryBuilderContract::class);

        $updateBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $updateBuilder
            ->expects($this->once())
            ->method('withSet')
            ->with(self::callback(static function (Value ...$values): bool {
                foreach ($values as $value) {
                    if ($value->name === 'name' && $value->value === 'Changed Name') {
                        return true;
                    }
                }

                return false;
            }))
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('update')
            ->with('test')
            ->willReturn($updateBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->statement
            ->expects($this->never())
            ->method('execute');

        $entity       = ($this->entityClass)::fromArray(['id' => 1, 'name' => 'Original Name']);
        $entity->name = 'Changed Name';

        $this->repository->update($entity);

        self::assertTrue(true);
    }

    public function testDeleteRemovesEntity(): void
    {
        $deleteBuilder = $this->createMock(DeleteQueryBuilderContract::class);

        $deleteBuilder
            ->expects($this->once())
            ->method('withWhere')
            ->willReturnSelf();

        $this->queryBuilderFactory
            ->expects($this->once())
            ->method('delete')
            ->with('test')
            ->willReturn($deleteBuilder);

        $this->manager
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderFactory);

        $this->manager
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($this->statement);

        $this->statement
            ->expects($this->never())
            ->method('execute');

        $entity = ($this->entityClass)::fromArray(['id' => 1, 'name' => 'To Delete']);

        $this->repository->delete($entity);

        self::assertTrue(true);
    }
}
