<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Manager;

use Override;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Manager\Abstract\PdoManager;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\PgsqlManager;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Throwable\Exception\OrmExecuteException;
use Valkyrja\Orm\Throwable\Exception\OrmNoPgsqlLastIdException;
use Valkyrja\Orm\Throwable\Exception\OrmStatementPreparationFailureException;
use Valkyrja\Tests\Fixtures\Orm\Entity\EntityIntIdFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class PgsqlManagerTest extends TestCase
{
    protected PDO&MockObject $pdo;

    protected ContainerContract&MockObject $container;

    protected PgsqlManager $manager;

    #[Override]
    protected function setUp(): void
    {
        $this->pdo       = $this->createMock(PDO::class);
        $this->container = $this->createMock(ContainerContract::class);
        $this->manager   = new PgsqlManager($this->pdo, $this->container);
    }

    public function testImplementsManagerContract(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        self::assertInstanceOf(ManagerContract::class, $this->manager);
    }

    public function testExtendsPdoManager(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        self::assertInstanceOf(PdoManager::class, $this->manager);
    }

    public function testLastInsertIdWithTableAndIdField(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('users_id_seq')
            ->willReturn('42');

        $result = $this->manager->lastInsertId('users', 'id');

        self::assertSame('42', $result);
    }

    public function testLastInsertIdWithCustomIdField(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('products_product_id_seq')
            ->willReturn('123');

        $result = $this->manager->lastInsertId('products', 'product_id');

        self::assertSame('123', $result);
    }

    public function testLastInsertIdWithoutTableAndIdField(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('table_id_seq')
            ->willReturn('99');

        $result = $this->manager->lastInsertId('table', 'id');

        self::assertSame('99', $result);
    }

    public function testLastInsertIdWithOnlyTable(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('users_id_seq')
            ->willReturn('50');

        $result = $this->manager->lastInsertId('users', 'id');

        self::assertSame('50', $result);
    }

    public function testLastInsertIdThrowsExceptionOnFailure(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('users_id_seq')
            ->willReturn(false);

        $this->expectException(OrmNoPgsqlLastIdException::class);
        $this->expectExceptionMessage('No last insert id found');

        $this->manager->lastInsertId('users', 'id');
    }

    public function testLastInsertIdGeneratesCorrectSequenceName(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->with('orders_order_id_seq')
            ->willReturn('1');

        $this->manager->lastInsertId('orders', 'order_id');
    }

    public function testCreateRepository(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');

        $repository = self::createStub(RepositoryContract::class);
        $registry   = new EntityMetadataRegistry();

        $this->container
            ->expects($this->once())
            ->method('getSingleton')
            ->with(EntityMetadataRegistryContract::class)
            ->willReturn($registry);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with(
                Repository::class,
                self::identicalTo([$this->manager, EntityIntIdFixture::class, $registry])
            )
            ->willReturn($repository);

        $result = $this->manager->createRepository(EntityIntIdFixture::class);

        self::assertInstanceOf(RepositoryContract::class, $result);
    }

    public function testCreateQueryBuilder(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $result = $this->manager->createQueryBuilder();

        self::assertInstanceOf(QueryBuilderFactoryContract::class, $result);
    }

    public function testBeginTransaction(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $result = $this->manager->beginTransaction();

        self::assertTrue($result);
    }

    public function testInTransaction(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true);

        $result = $this->manager->inTransaction();

        self::assertTrue($result);
    }

    public function testEnsureTransactionStartsTransactionWhenNotInTransaction(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(false);

        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->manager->ensureTransaction();
    }

    public function testEnsureTransactionDoesNotStartWhenAlreadyInTransaction(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true);

        $this->pdo
            ->expects($this->never())
            ->method('beginTransaction');

        $this->manager->ensureTransaction();
    }

    public function testPrepare(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $pdoStatement = self::createStub(PDOStatement::class);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users')
            ->willReturn($pdoStatement);

        $result = $this->manager->prepare('SELECT * FROM users');

        self::assertInstanceOf(StatementContract::class, $result);
    }

    public function testPrepareThrowsExceptionOnFailure(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INVALID QUERY')
            ->willReturn(false);

        $this->expectException(OrmStatementPreparationFailureException::class);
        $this->expectExceptionMessage('Statement preparation has failed');

        $this->manager->prepare('INVALID QUERY');
    }

    public function testQueryExecutesPreparedStatement(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $pdoStatement = $this->createMock(PDOStatement::class);

        $pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users')
            ->willReturn($pdoStatement);

        $result = $this->manager->query('SELECT * FROM users');

        self::assertInstanceOf(StatementContract::class, $result);
    }

    public function testQueryThrowsExceptionOnPreparationFailure(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INVALID QUERY')
            ->willReturn(false);

        $this->expectException(OrmStatementPreparationFailureException::class);
        $this->expectExceptionMessage('Statement preparation has failed');

        $this->manager->query('INVALID QUERY');
    }

    public function testQueryThrowsExceptionWithErrorMessageOnExecutionFailure(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $pdoStatement = $this->createMock(PDOStatement::class);

        $pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $pdoStatement
            ->method('errorInfo')
            ->willReturn(['HY000', 1, 'Driver error']);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users')
            ->willReturn($pdoStatement);

        $this->expectException(OrmExecuteException::class);
        $this->expectExceptionMessage('Driver error');

        $this->manager->query('SELECT * FROM users');
    }

    public function testQueryThrowsExceptionWithFallbackMessageOnExecutionFailure(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $pdoStatement = $this->createMock(PDOStatement::class);

        $pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $pdoStatement
            ->method('errorInfo')
            ->willReturn(['00000', null, null]);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users')
            ->willReturn($pdoStatement);

        $this->expectException(OrmExecuteException::class);
        $this->expectExceptionMessage('Statement execution has failed');

        $this->manager->query('SELECT * FROM users');
    }

    public function testCommit(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $result = $this->manager->commit();

        self::assertTrue($result);
    }

    public function testRollback(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('rollBack')
            ->willReturn(true);

        $result = $this->manager->rollback();

        self::assertTrue($result);
    }
}
