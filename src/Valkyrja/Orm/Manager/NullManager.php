<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Manager;

use Override;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\QueryBuilder\Factory\SqlQueryBuilderFactory;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Statement\Contract\StatementContract;
use Valkyrja\Orm\Statement\NullStatement;

class NullManager implements ManagerContract
{
    public function __construct(
        protected EntityMetadataRegistryContract $registry = new EntityMetadataRegistry(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createRepository(string $entity): RepositoryContract
    {
        return new Repository($this, $entity, $this->registry);
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
    public function prepare(string $query): StatementContract
    {
        return new NullStatement();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function query(string $query): StatementContract
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
    public function lastInsertId(string $table, string $idField): string
    {
        return 'id';
    }
}
