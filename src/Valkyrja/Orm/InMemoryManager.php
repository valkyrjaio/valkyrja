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

namespace Valkyrja\Orm;

use Override;
use Valkyrja\Orm\Contract\Manager as Contract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactory;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\Orm\Statement\NullStatement;

/**
 * Class InMemoryManager.
 *
 * @author Melech Mizrachi
 */
class InMemoryManager implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function createRepository(string $entity): Repository
    {
        return new \Valkyrja\Orm\Repository\Repository($this, $entity);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createQueryBuilder(): QueryBuilderFactory
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
    public function prepare(string $query): Statement
    {
        // TODO: Implement prepare() method.
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function query(string $query): Statement
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
