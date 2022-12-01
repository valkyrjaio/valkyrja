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

namespace Valkyrja\ORM;

use Valkyrja\ORM\Enums\WhereType;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;

/**
 * Interface Repository.
 *
 * @author   Melech Mizrachi
 * @template T
 */
interface Repository
{
    /**
     * Find by given criteria.
     *
     * @return static<T>
     */
    public function find(): self;

    /**
     * Find a single entity given its id.
     *
     * @param int|string $id The id
     *
     * @return static<T>
     */
    public function findOne(int|string $id): self;

    /**
     * Count all the results of given criteria.
     *
     * @return static<T>
     */
    public function count(): self;

    /**
     * Set columns.
     *
     * @param array $columns The columns
     *
     * @return static<T>
     */
    public function columns(array $columns): self;

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string      $column   The column
     * @param string|null $operator [optional]
     * @param mixed|null  $value    [optional]
     * @param bool        $setType  [optional]
     *
     * @return static<T>
     */
    public function where(string $column, string $operator = null, mixed $value = null, bool $setType = true): self;

    /**
     * Start a where clause in parentheses.
     *
     * @return static<T>
     */
    public function startWhereGroup(): self;

    /**
     * End a where clause in parentheses.
     *
     * @return static<T>
     */
    public function endWhereGroup(): self;

    /**
     * Add a where type.
     *
     * @param WhereType $type The type
     *
     * @return static<T>
     */
    public function whereType(WhereType $type = WhereType::AND): self;

    /**
     * Join with another table.
     *
     * @param string      $table    The table to join on
     * @param string      $column1  The column to join on
     * @param string      $column2  The secondary column to join on
     * @param string|null $operator [optional] The operator
     * @param string|null $type     [optional] The type of join
     * @param bool|null   $isWhere  [optional] Whether this is a where join
     *
     * @return static<T>
     */
    public function join(
        string $table,
        string $column1,
        string $column2,
        string $operator = null,
        string $type = null,
        bool $isWhere = null
    ): self;

    /**
     * Set an order by.
     *
     * @param string      $column    The column
     * @param string|null $direction [optional] The order direction
     *
     * @return static<T>
     */
    public function orderBy(string $column, string $direction = null): self;

    /**
     * Set limit.
     *
     * @param int $limit The limit
     *
     * @return static<T>
     */
    public function limit(int $limit): self;

    /**
     * Set offset.
     *
     * @param int $offset The offset
     *
     * @return static<T>
     */
    public function offset(int $offset): self;

    /**
     * Add relationships to include with the results.
     *
     * @param array|null $relationships [optional] The relationships to get
     *
     * @return static<T>
     */
    public function withRelationships(array $relationships = null): self;

    /**
     * Get results.
     *
     * @return T[]|Entity[]
     */
    public function getResult(): array;

    /**
     * Get one or null.
     *
     * @return T|null
     */
    public function getOneOrNull(): ?Entity;

    /**
     * Get one or fail.
     *
     * @throws EntityNotFoundException
     *
     * @return T|Entity
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
     * @param Entity|T $entity The entity
     * @param bool     $defer  [optional] Whether to defer creation or create immediately
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
     * @param Entity|T $entity The entity
     * @param bool     $defer  [optional] Whether to defer save or save immediately
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
     * @param Entity|T $entity The entity
     * @param bool     $defer  [optional] Whether to deletion creation or delete immediately
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
     * @param Entity|T $entity The entity
     * @param bool     $defer  [optional] Whether to defer deletion or delete immediately
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
     * @param Entity|T|null $entity The entity instance to remove.
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
     * @param string|null $alias The alias to use
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder;

    /**
     * Create a new query.
     *
     * @param string $query The query string
     *
     * @return Query
     */
    public function createQuery(string $query): Query;

    /**
     * Get the retriever.
     *
     * @return Retriever
     */
    public function getRetriever(): Retriever;

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister;
}
