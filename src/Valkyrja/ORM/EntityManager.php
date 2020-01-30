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

/**
 * Interface EntityManager.
 *
 * @author Melech Mizrachi
 */
interface EntityManager
{
    /**
     * Get a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function queryBuilder(string $entity = null, string $alias = null): QueryBuilder;

    /**
     * Start a query.
     *
     * @param string      $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function query(string $query, string $entity = null): Query;

    /**
     * Get a repository instance.
     *
     * @param string $entity
     *
     * @return Repository
     */
    public function repository(string $entity): Repository;

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool;

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
     * @return string
     */
    public function lastInsertId(): string;

    /**
     * Find a single entity given its id.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function find(string $entity, $id, bool $getRelations = null): ?Entity;

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityManager
     *          ->findBy(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array;

    /**
     * Find one entity by given criteria.
     * <code>
     *      $repository
     *          ->findOneBy(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity
     */
    public function findOneBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): Entity;

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityManager
     *          ->findBy(
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ]
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = null
    ): array;

    /**
     * Count all the results of given criteria.
     * <code>
     *      $entityManager
     *          ->count(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ]
     *          )
     * </code>.
     *
     * @param string $entity
     * @param array  $criteria
     *
     * @return int
     */
    public function count(string $entity, array $criteria): int;

    /**
     * Set a model for creation on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function create(Entity $entity): void;

    /**
     * Set a model for saving on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function save(Entity $entity): void;

    /**
     * Set a model for deletion on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function delete(Entity $entity): void;

    /**
     * Remove a model previously set for creation, save, or deletion.
     *
     * @param Entity $entity The entity instance to remove.
     *
     * @return bool
     */
    public function remove(Entity $entity): bool;
}
