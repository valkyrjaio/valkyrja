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
use Valkyrja\Orm\Manager\Contract\Manager as Contract;
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
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inTransaction(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ensureTransaction(): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function prepare(string $query): Statement
    {
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function query(string $query): Statement
    {
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function commit(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function rollback(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
    {
        return 'id';
    }
}
