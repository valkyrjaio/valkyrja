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

namespace Valkyrja\Orm\Driver\Contract;

use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Retriever\Contract\Retriever;
use Valkyrja\Orm\Statement\Contract\Statement;

/**
 * Interface Driver.
 *
 * @author Melech Mizrachi
 */
interface Driver
{
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
     * Rollback the previous transaction.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement;

    /**
     * Get the last inserted id.
     *
     * @param string|null $table   [optional] The table last inserted into
     * @param string|null $idField [optional] The id field of the table last inserted into
     *
     * @return string
     */
    public function lastInsertId(?string $table = null, ?string $idField = null): string;

    /**
     * Create a new query instance.
     *
     * @param string|null               $query
     * @param class-string<Entity>|null $entity
     *
     * @return Query
     */
    public function createQuery(?string $query = null, ?string $entity = null): Query;

    /**
     * Create a new query builder instance.
     *
     * @param class-string<Entity>|null $entity
     * @param string|null               $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(?string $entity = null, ?string $alias = null): QueryBuilder;

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever;

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister;
}
