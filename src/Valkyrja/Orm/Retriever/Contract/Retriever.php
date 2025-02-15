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

namespace Valkyrja\Orm\Retriever\Contract;

use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Exception\EntityNotFoundException;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\WhereQueryBuilder;

/**
 * Interface Retriever.
 *
 * @author   Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * https://phpstan.org/r/157d3b9a-d646-479e-bcad-46ea0f44cb56
 */
interface Retriever extends WhereQueryBuilder
{
    /**
     * Find by given criteria.
     *
     * <code>
     *      $retriever->bind(Entity::class, true | false)
     * </code>
     *
     * @template EntityFind of Entity
     *
     * @param class-string<EntityFind> $entity
     *
     * @return static<EntityFind>
     */
    public function find(string $entity): self;

    /**
     * Find a single entity given its id.
     *
     * <code>
     *      $retriever->findOne(Entity::class, 1, true | false)
     * </code>
     *
     * @template EntityFindOne of Entity
     *
     * @param class-string<EntityFindOne> $entity
     * @param int|string                  $id
     *
     * @return static<EntityFindOne>
     */
    public function findOne(string $entity, int|string $id): self;

    /**
     * Count all the results of given criteria.
     *
     * <code>
     *      $retriever->count(Entity::class)
     * </code>
     *
     * @template EntityCount of Entity
     *
     * @param class-string<EntityCount> $entity
     *
     * @return static<EntityCount>
     */
    public function count(string $entity): self;

    /**
     * Set columns.
     *
     * @param string ...$columns
     *
     * @return static
     */
    public function columns(string ...$columns): static;

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
     * Set group by.
     *
     * @param string $column
     *
     * @return static
     */
    public function groupBy(string $column): static;

    /**
     * Set an order by.
     *
     * @param string      $column
     * @param string|null $type
     *
     * @return static
     */
    public function orderBy(string $column, ?string $type = null): static;

    /**
     * Set limit.
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): static;

    /**
     * Set offset.
     *
     * @param int $offset
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
     * Get the query builder.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder;

    /**
     * Get the query.
     *
     * @return Query
     */
    public function getQuery(): Query;
}
