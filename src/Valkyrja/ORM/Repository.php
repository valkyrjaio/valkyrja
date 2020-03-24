<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use Valkyrja\ORM\Exceptions\EntityNotFoundException;

/**
 * Interface Repository.
 *
 * @author Melech Mizrachi
 */
interface Repository
{
    /**
     * Make a new repository.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     *
     * @return static
     */
    public static function make(EntityManager $entityManager, string $entity): self;

    /**
     * Set the adapter to use.
     *
     * @param string $adapter
     *
     * @return static
     */
    public function setAdapter(string $adapter): self;

    /**
     * Get the connection.
     *
     * @return Connection
     */
    public function getConnection(): Connection;

    /**
     * Set the connection to use.
     *
     * @param string $connection
     *
     * @return static
     */
    public function setConnection(string $connection): self;

    /**
     * Find by given criteria.
     *
     * @param bool|null $getRelations
     *
     * @return static
     */
    public function find(bool $getRelations = false): self;

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return static
     */
    public function findOne($id, bool $getRelations = false): self;

    /**
     * Count all the results of given criteria.
     *
     * @return static
     */
    public function count(): self;

    /**
     * Set columns.
     *
     * @param array $columns
     *
     * @return static
     */
    public function columns(array $columns): self;

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function where(string $column, string $operator = null, $value = null): self;

    /**
     * Add an additional `OR` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function orWhere(string $column, string $operator = null, $value = null): self;

    /**
     * Set an order by.
     *
     * @param string      $column
     * @param string|null $type
     *
     * @return static
     */
    public function orderBy(string $column, string $type = null): self;

    /**
     * Set limit.
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): self;

    /**
     * Set offset.
     *
     * @param int $offset
     *
     * @return static
     */
    public function offset(int $offset): self;

    /**
     * Get results.
     *
     * @return Entity[]
     */
    public function getResults(): array;

    /**
     * Get one or null.
     *
     * @return Entity|null
     */
    public function getOneOrNull(): ?Entity;

    /**
     * Get one or fail.
     *
     * @throws EntityNotFoundException
     *
     * @return Entity
     */
    public function getOneOrFail(): Entity;

    /**
     * Get count results.
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Create a new entity.
     *
     * <code>
     *      $repository->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void;

    /**
     * Update an existing entity.
     *
     * <code>
     *      $repository->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void;

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $repository->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void;

    /**
     * Soft delete an existing entity.
     *
     * <code>
     *      $persister->softDelete(new SoftDeleteEntity(), true | false)
     * </code>
     *
     * @param SoftDeleteEntity $entity
     * @param bool             $defer [optional]
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void;

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $repository->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void;

    /**
     * Persist all entities.
     *
     * @return bool
     */
    public function persist(): bool;

    /**
     * Get a new query builder instance.
     *
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder;

    /**
     * Create a new query.
     *
     * @param string $query
     *
     * @return Query
     */
    public function createQuery(string $query): Query;
}
