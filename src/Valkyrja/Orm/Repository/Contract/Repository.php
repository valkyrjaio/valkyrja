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

namespace Valkyrja\Orm\Repository\Contract;

use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\Exception\EntityNotFoundException;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Retriever\Contract\Retriever;

/**
 * Interface Repository.
 *
 * @author   Melech Mizrachi
 *
 * @template Entity of Entity
 */
interface Repository
{
    /**
     * Find by given criteria.
     *
     * @return static
     */
    public function find(): static;

    /**
     * Find a single entity given its id.
     *
     * @param int|string $id The id
     *
     * @return static
     */
    public function findOne(int|string $id): static;

    /**
     * Count all the results of given criteria.
     *
     * @return static
     */
    public function count(): static;

    /**
     * Set columns.
     *
     * @param string ...$columns The columns
     *
     * @return static
     */
    public function columns(string ...$columns): static;

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string                                                                    $column   The column
     * @param string|null                                                               $operator [optional]
     * @param QueryBuilder|array<string|float|int|bool|null>|string|float|int|bool|null $value    [optional]
     * @param bool                                                                      $setType  [optional]
     *
     * @return static
     */
    public function where(
        string $column,
        ?string $operator = null,
        QueryBuilder|array|string|float|int|bool|null $value = null,
        bool $setType = true
    ): static;

    /**
     * Start a where clause in parentheses.
     *
     * @return static
     */
    public function startWhereGroup(): static;

    /**
     * End a where clause in parentheses.
     *
     * @return static
     */
    public function endWhereGroup(): static;

    /**
     * Add a where type.
     *
     * @param WhereType $type The type
     *
     * @return static
     */
    public function whereType(WhereType $type = WhereType::AND): static;

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
     * @return static
     */
    public function join(
        string $table,
        string $column1,
        string $column2,
        ?string $operator = null,
        ?string $type = null,
        ?bool $isWhere = null
    ): static;

    /**
     * Set an order by.
     *
     * @param string      $column    The column
     * @param string|null $direction [optional] The order direction
     *
     * @return static
     */
    public function orderBy(string $column, ?string $direction = null): static;

    /**
     * Set limit.
     *
     * @param int $limit The limit
     *
     * @return static
     */
    public function limit(int $limit): static;

    /**
     * Set offset.
     *
     * @param int $offset The offset
     *
     * @return static
     */
    public function offset(int $offset): static;

    /**
     * Get results.
     *
     * @return Entity[]
     */
    public function getResult(): array;

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
     * @param Entity $entity The entity
     * @param bool   $defer  [optional] Whether to defer creation or create immediately
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
     * @param Entity $entity The entity
     * @param bool   $defer  [optional] Whether to defer save or save immediately
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
     * @param Entity $entity The entity
     * @param bool   $defer  [optional] Whether to deletion creation or delete immediately
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void;

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $repository->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity The entity instance to remove
     *
     * @return void
     */
    public function clear(?Entity $entity = null): void;

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
    public function createQueryBuilder(?string $alias = null): QueryBuilder;

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
     * @return Retriever<Entity>
     */
    public function getRetriever(): Retriever;

    /**
     * Get the persister.
     *
     * @return Persister<Entity>
     */
    public function getPersister(): Persister;
}
