<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use InvalidArgumentException;
use PDO;
use Valkyrja\ORM\Model;

/**
 * Interface EntityManager.
 */
interface EntityManager
{
    /**
     * Get a store by the connection name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return PDO
     */
    public function store(string $name = null): PDO;

    /**
     * Get a new query builder instance.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder;

    /**
     * Get a repository instance.
     *
     * @param string $model
     *
     * @return Repository
     */
    public function getRepository(string $model): Repository;

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * Set a model for creation on transaction commit.
     *
     * @param Model $model
     *
     * @return void
     */
    public function create(Model $model): void;

    /**
     * Set a model for saving on transaction commit.
     *
     * @param Model $model
     *
     * @return void
     */
    public function save(Model $model): void;

    /**
     * Remove a model previously set for creation or save.
     *
     * @param Model $model The entity instance to remove.
     *
     * @return bool
     */
    public function remove(Model $model): bool;

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
}
