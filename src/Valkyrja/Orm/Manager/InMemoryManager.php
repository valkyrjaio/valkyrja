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

namespace Valkyrja\Orm\Manager;

use Override;
use Valkyrja\Orm\Manager\Contract\ManagerContract as Contract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository as OrmRepository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\NullStatement;

class InMemoryManager implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function createRepository(string $entity): RepositoryContract
    {
        return new OrmRepository($this, $entity);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createQueryBuilder(): QueryBuilderFactoryContract
    {
        return new SqlQueryBuilderFactory();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function beginTransaction(): bool
    {
        // TODO: Implement beginTransaction() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inTransaction(): bool
    {
        // TODO: Implement inTransaction() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ensureTransaction(): void
    {
        // TODO: Implement ensureTransaction() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function prepare(string $query): StatementContract
    {
        // TODO: Implement prepare() method.
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function query(string $query): StatementContract
    {
        // TODO: Implement query() method.
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function commit(): bool
    {
        // TODO: Implement commit() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rollback(): bool
    {
        // TODO: Implement rollback() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        // TODO: Implement lastInsertId() method.
        return 'id';
    }
}
