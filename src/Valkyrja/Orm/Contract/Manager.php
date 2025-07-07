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

namespace Valkyrja\Orm\Contract;

use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactory;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Statement\Contract\Statement;

/**
 * Interface Manager.
 *
 * @author Melech Mizrachi
 */
interface Manager
{
    /**
     * Create a repository for a given entity.
     *
     * @template T of Entity
     *
     * @param class-string<T> $entity The entity
     *
     * @return Repository<T>
     */
    public function createRepository(string $entity): Repository;

    /**
     * Create a query builder.
     *
     * @return QueryBuilderFactory
     */
    public function createQueryBuilder(): QueryBuilderFactory;

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool;

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void;

    /**
     * Prepare a query.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement;

    /**
     * Run a query.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function query(string $query): Statement;

    /**
     * Commit all items in the transaction.
     *
     * @return bool
     */
    public function commit(): bool;

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool;

    /**
     * Get the last inserted id.
     *
     * @param non-empty-string|null $table   [optional] The table last inserted into
     * @param non-empty-string|null $idField [optional] The id field of the table last inserted into
     *
     * @return non-empty-string
     */
    public function lastInsertId(string|null $table = null, string|null $idField = null): string;
}
