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

namespace Valkyrja\ORM\Drivers;

use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Driver as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Statement;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->adapter->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->adapter->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        $this->adapter->ensureTransaction();
    }

    /**
     * Commit all items in the transaction.
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->adapter->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->adapter->rollback();
    }

    /**
     * Rollback the previous transaction.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement
    {
        return $this->adapter->prepare($query);
    }

    /**
     * Get the last inserted id.
     *
     * @param string|null $table [optional] The table last inserted into
     * @param string|null $idField [optional] The id field of the table last inserted into
     *
     * @return string
     */
    public function lastInsertId(string $table = null, string $idField = null): string
    {
        return $this->adapter->lastInsertId($table, $idField);
    }

    /**
     * Create a new query instance.
     *
     * @param string|null $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function createQuery(string $query = null, string $entity = null): Query
    {
        return $this->adapter->createQuery($query, $entity);
    }

    /**
     * Create a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        return $this->adapter->createQueryBuilder($entity, $alias);
    }

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return $this->adapter->createRetriever();
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->adapter->getPersister();
    }
}
