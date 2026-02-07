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

use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\NullManager;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\NullStatement;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullManagerTest extends TestCase
{
    protected NullManager $manager;

    protected function setUp(): void
    {
        $this->manager = new NullManager();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(ManagerContract::class, $this->manager);
    }

    public function testCreateRepositoryReturnsRepository(): void
    {
        $repository = $this->manager->createRepository('SomeEntity');

        self::assertInstanceOf(RepositoryContract::class, $repository);
        self::assertInstanceOf(Repository::class, $repository);
    }

    public function testCreateQueryBuilderReturnsFactory(): void
    {
        $factory = $this->manager->createQueryBuilder();

        self::assertInstanceOf(QueryBuilderFactoryContract::class, $factory);
        self::assertInstanceOf(SqlQueryBuilderFactory::class, $factory);
    }

    public function testBeginTransactionReturnsTrue(): void
    {
        self::assertTrue($this->manager->beginTransaction());
    }

    public function testInTransactionReturnsTrue(): void
    {
        self::assertTrue($this->manager->inTransaction());
    }

    public function testEnsureTransactionDoesNothing(): void
    {
        // Should not throw any exception
        $this->manager->ensureTransaction();

        self::assertTrue(true);
    }

    public function testPrepareReturnsNullStatement(): void
    {
        $statement = $this->manager->prepare('SELECT * FROM users');

        self::assertInstanceOf(StatementContract::class, $statement);
        self::assertInstanceOf(NullStatement::class, $statement);
    }

    public function testQueryReturnsNullStatement(): void
    {
        $statement = $this->manager->query('SELECT * FROM users');

        self::assertInstanceOf(StatementContract::class, $statement);
        self::assertInstanceOf(NullStatement::class, $statement);
    }

    public function testCommitReturnsTrue(): void
    {
        self::assertTrue($this->manager->commit());
    }

    public function testRollbackReturnsTrue(): void
    {
        self::assertTrue($this->manager->rollback());
    }

    public function testLastInsertIdReturnsIdString(): void
    {
        self::assertSame('id', $this->manager->lastInsertId());
    }

    public function testLastInsertIdWithTableReturnsIdString(): void
    {
        self::assertSame('id', $this->manager->lastInsertId('users'));
    }

    public function testLastInsertIdWithTableAndFieldReturnsIdString(): void
    {
        self::assertSame('id', $this->manager->lastInsertId('users', 'user_id'));
    }
}
