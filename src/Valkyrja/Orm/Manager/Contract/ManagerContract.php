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

namespace Valkyrja\Orm\Manager\Contract;

use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\QueryBuilder\Factory\Contract\QueryBuilderFactoryContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Orm\Statement\Contract\StatementContract;

interface ManagerContract
{
    /**
     * Create a repository for a given entity.
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity
     *
     * @return RepositoryContract<T>
     */
    public function createRepository(string $entity): RepositoryContract;

    /**
     * Create a query builder.
     *
     * @return QueryBuilderFactoryContract
     */
    public function createQueryBuilder(): QueryBuilderFactoryContract;

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
     * @return StatementContract
     */
    public function prepare(string $query): StatementContract;

    /**
     * Run a query.
     *
     * @param string $query The query
     *
     * @return StatementContract
     */
    public function query(string $query): StatementContract;

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
