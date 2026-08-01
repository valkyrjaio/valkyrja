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
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Data\DatedMetadata;
use Valkyrja\Orm\Data\EntityMetadata;
use Valkyrja\Orm\Data\SoftDeleteMetadata;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Throwable\Exception\OrmUnregisteredEntityException;
use Valkyrja\Tests\Fixtures\Orm\Entity\DatedEntityCustomFieldsFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\DatedEntityFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityIntIdFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityStringIdFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\SoftDeleteEntityCustomFieldFixture;
use Valkyrja\Tests\Fixtures\Orm\Entity\SoftDeleteEntityFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class RepositoryTest extends TestCase
{
    protected ManagerContract&MockObject $manager;

    protected QueryBuilderFactoryContract&MockObject $queryBuilderFactory;

    protected StatementContract&MockObject $statement;

    protected Repository $repository;

    protected EntityMetadataRegistry $registry;

    /** @var class-string<EntityContract> */
    protected string $entityClass;

    protected function setUp(): void
    {
        $this->manager             = $this->createMock(ManagerContract::class);
        $this->queryBuilderFactory = $this->createMock(QueryBuilderFactoryContract::class);
        $this->statement           = $this->createMock(StatementContract::class);

        $this->entityClass = EntityIntIdFixture::class;
        $this->registry    = new EntityMetadataRegistry();

        $this->repository = new Repository($this->manager, $this->entityClass, $this->registry);
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
            ->method('fetchAllEntities')
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
            ->method('fetchAllEntities')
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
            ->method('fetchAllEntities')
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
            ->method('fetchAllEntities')
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
            ->method('fetchAllEntities')
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

        $entity = EntityStringIdFixture::fromArray(['name' => 'New Entity']);

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

    public function testForceDeleteRemovesEntity(): void
    {
        $this->expectDeleteStatement();

        $entity = ($this->entityClass)::fromArray(['id' => 1, 'name' => 'To Delete']);

        $this->repository->forceDelete($entity);

        self::assertTrue(true);
    }

    public function testCreateStampsCreatedAndModifiedDates(): void
    {
        $this->expectInsertStatement();

        $repository = $this->repositoryFor(
            DatedEntityFixture::class,
            new EntityMetadata(dated: new DatedMetadata())
        );

        $entity = DatedEntityFixture::fromArray(['name' => 'New Entity']);

        $repository->create($entity);

        self::assertNotEmpty($entity->date_created);
        self::assertSame($entity->date_created, $entity->date_modified);
    }

    public function testCreateStampsWithTheRegisteredFieldsAndFormat(): void
    {
        $this->expectInsertStatement();

        $repository = $this->repositoryFor(
            DatedEntityCustomFieldsFixture::class,
            new EntityMetadata(
                dated: new DatedMetadata(
                    format: DateFormat::MICROSECOND,
                    dateCreatedField: 'created_at',
                    dateModifiedField: 'updated_at'
                )
            )
        );

        $entity = DatedEntityCustomFieldsFixture::fromArray(['name' => 'New Entity']);

        $repository->create($entity);

        self::assertNotEmpty($entity->created_at);
        self::assertSame($entity->created_at, $entity->updated_at);
    }

    public function testCreateDoesNotStampAnUndatedEntity(): void
    {
        $this->expectInsertStatement();

        $entity = ($this->entityClass)::fromArray(['name' => 'New Entity']);

        $this->repository->create($entity);

        self::assertArrayNotHasKey('date_created', $entity->asStorableArray());
    }

    public function testCreateThrowsWhenTheEntityIsNotRegistered(): void
    {
        $repository = new Repository($this->manager, DatedEntityFixture::class, $this->registry);

        $entity = DatedEntityFixture::fromArray(['name' => 'New Entity']);

        $this->expectNoStatement();

        $this->expectException(OrmUnregisteredEntityException::class);

        $repository->create($entity);
    }

    public function testCreateThrowsWhenTheMetadataHoldsNoDatedMetadata(): void
    {
        $repository = $this->repositoryFor(DatedEntityFixture::class, new EntityMetadata());

        $entity = DatedEntityFixture::fromArray(['name' => 'New Entity']);

        $this->expectNoStatement();

        $this->expectException(OrmUnregisteredEntityException::class);
        $this->expectExceptionMessage('holds no dated metadata');

        $repository->create($entity);
    }

    public function testUpdateStampsTheModifiedDateOnly(): void
    {
        $this->expectUpdateStatement();

        $repository = $this->repositoryFor(
            DatedEntityFixture::class,
            new EntityMetadata(dated: new DatedMetadata())
        );

        $entity = DatedEntityFixture::fromArray([
            'id'            => 1,
            'name'          => 'Existing',
            'date_created'  => '01-26-2026 12:00:00 UTC',
            'date_modified' => '01-26-2026 12:00:00 UTC',
        ]);

        $repository->update($entity);

        self::assertSame('01-26-2026 12:00:00 UTC', $entity->date_created);
        self::assertNotSame('01-26-2026 12:00:00 UTC', $entity->date_modified);
    }

    public function testUpdateDoesNotStampAnUndatedEntity(): void
    {
        $this->expectUpdateStatement();

        $entity = ($this->entityClass)::fromArray(['id' => 1, 'name' => 'Existing']);

        $this->repository->update($entity);

        self::assertArrayNotHasKey('date_modified', $entity->asStorableArray());
    }

    public function testUpdateThrowsWhenTheMetadataHoldsNoDatedMetadata(): void
    {
        $repository = $this->repositoryFor(DatedEntityFixture::class, new EntityMetadata());

        $entity = DatedEntityFixture::fromArray(['id' => 1, 'name' => 'Existing']);

        $this->expectNoStatement();

        $this->expectException(OrmUnregisteredEntityException::class);
        $this->expectExceptionMessage('holds no dated metadata');

        $repository->update($entity);
    }

    public function testDeleteSoftDeletesASoftDeleteEntity(): void
    {
        $this->expectUpdateStatement();

        $repository = $this->repositoryFor(
            SoftDeleteEntityFixture::class,
            new EntityMetadata(softDelete: new SoftDeleteMetadata())
        );

        $entity = SoftDeleteEntityFixture::fromArray(['id' => 1, 'name' => 'To Soft Delete']);

        $repository->delete($entity);

        self::assertNotNull($entity->date_deleted);
    }

    public function testDeleteSoftDeletesWithTheRegisteredFieldAndFormat(): void
    {
        $this->expectUpdateStatement();

        $repository = $this->repositoryFor(
            SoftDeleteEntityCustomFieldFixture::class,
            new EntityMetadata(
                softDelete: new SoftDeleteMetadata(
                    format: DateFormat::MILLISECOND,
                    dateDeletedField: 'deleted_at'
                )
            )
        );

        $entity = SoftDeleteEntityCustomFieldFixture::fromArray(['id' => 1, 'name' => 'To Soft Delete']);

        $repository->delete($entity);

        self::assertNotNull($entity->deleted_at);
    }

    public function testDeleteThrowsWhenTheMetadataHoldsNoSoftDeleteMetadata(): void
    {
        $repository = $this->repositoryFor(SoftDeleteEntityFixture::class, new EntityMetadata());

        $entity = SoftDeleteEntityFixture::fromArray(['id' => 1, 'name' => 'To Soft Delete']);

        $this->expectNoStatement();

        $this->expectException(OrmUnregisteredEntityException::class);
        $this->expectExceptionMessage('holds no soft delete metadata');

        $repository->delete($entity);
    }

    public function testForceDeleteRemovesTheRowOfASoftDeleteEntity(): void
    {
        $this->expectDeleteStatement();

        $repository = $this->repositoryFor(
            SoftDeleteEntityFixture::class,
            new EntityMetadata(softDelete: new SoftDeleteMetadata())
        );

        $entity = SoftDeleteEntityFixture::fromArray(['id' => 1, 'name' => 'To Remove']);

        $repository->forceDelete($entity);

        self::assertNull($entity->date_deleted);
    }

    /**
     * Build a repository whose registry holds the given metadata for the entity.
     *
     * @param class-string<EntityContract> $entity The entity
     *
     * @return Repository<EntityContract>
     */
    private function repositoryFor(string $entity, EntityMetadata $metadata): Repository
    {
        return new Repository(
            $this->manager,
            $entity,
            $this->registry->withEntity($entity, $metadata)
        );
    }

    /**
     * Expect the repository to build no statement at all.
     */
    private function expectNoStatement(): void
    {
        $this->manager
            ->expects($this->never())
            ->method('createQueryBuilder');

        $this->manager
            ->expects($this->never())
            ->method('prepare');

        $this->queryBuilderFactory
            ->expects($this->never())
            ->method('insert');

        $this->statement
            ->expects($this->never())
            ->method('execute');
    }

    /**
     * Expect the manager to prepare one INSERT statement.
     */
    private function expectInsertStatement(): void
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
    }

    /**
     * Expect the manager to prepare one UPDATE statement.
     */
    private function expectUpdateStatement(): void
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
    }

    /**
     * Expect the manager to prepare one DELETE statement.
     */
    private function expectDeleteStatement(): void
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
    }
}
