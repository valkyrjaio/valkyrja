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

namespace Valkyrja\Tests\Unit\Orm\Manager;

use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Manager\Abstract\PdoManager;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Classes\Orm\Entity\EntityIntIdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class SqliteManagerTest extends TestCase
{
    protected PDO&MockObject $pdo;

    protected ContainerContract&MockObject $container;

    protected SqliteManager $manager;

    protected function setUp(): void
    {
        $this->pdo       = $this->createMock(PDO::class);
        $this->container = $this->createMock(ContainerContract::class);
        $this->manager   = new SqliteManager($this->pdo, $this->container);
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

    public function testLastInsertId(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('42');

        $result = $this->manager->lastInsertId();

        self::assertSame('42', $result);
    }

    public function testLastInsertIdIgnoresTableAndIdFieldParameters(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('123');

        $result = $this->manager->lastInsertId('users', 'id');

        self::assertSame('123', $result);
    }

    public function testLastInsertIdThrowsExceptionOnFailure(): void
    {
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(false);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No last insert id found');

        $this->manager->lastInsertId();
    }

    public function testCreateRepository(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');

        $repository = self::createStub(RepositoryContract::class);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($repository);

        $result = $this->manager->createRepository(EntityIntIdClass::class);

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

        self::assertTrue(true);
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

        self::assertTrue(true);
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

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Statement preparation has failed');

        $this->manager->prepare('INVALID QUERY');
    }

    public function testQuery(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $pdoStatement = self::createStub(PDOStatement::class);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users')
            ->willReturn($pdoStatement);

        $result = $this->manager->query('SELECT * FROM users');

        self::assertInstanceOf(StatementContract::class, $result);
    }

    public function testQueryThrowsExceptionOnFailure(): void
    {
        $this->pdo->expects($this->never())->method('lastInsertId');
        $this->container->expects($this->never())->method('get');

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INVALID QUERY')
            ->willReturn(false);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Statement query has failed');

        $this->manager->query('INVALID QUERY');
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
