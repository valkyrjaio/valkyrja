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

use Valkyrja\Orm\Contract\Manager as Contract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactory;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\Orm\Statement\NullStatement;

/**
 * Class NullManager.
 *
 * @author Melech Mizrachi
 */
class NullManager implements Contract
{
    /**
     * @inheritDoc
     */
    public function createRepository(string $entity): Repository
    {
        return new \Valkyrja\Orm\Repository\Repository($this, $entity);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(): QueryBuilderFactory
    {
        return new SqlQueryBuilderFactory();
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function ensureTransaction(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): Statement
    {
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    public function query(string $query): Statement
    {
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        return 'id';
    }
}
