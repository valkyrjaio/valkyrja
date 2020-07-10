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

use Valkyrja\ORM\Exceptions\EntityNotFoundException;

/**
 * Interface Retriever
 *
 * @author Melech Mizrachi
 */
interface Retriever
{
    /**
     * Find by given criteria.
     *
     * <code>
     *      $retriever->bind(Entity::class, true | false)
     * </code>
     *
     * @param string $entity
     *
     * @return static
     */
    public function find(string $entity): self;

    /**
     * Find a single entity given its id.
     *
     * <code>
     *      $retriever->findOne(Entity::class, 1, true | false)
     * </code>
     *
     * @param string     $entity
     * @param string|int $id
     *
     * @return static
     */
    public function findOne(string $entity, $id): self;

    /**
     * Count all the results of given criteria.
     *
     * <code>
     *      $retriever->count(Entity::class)
     * </code>
     *
     * @param string $entity
     *
     * @return static
     */
    public function count(string $entity): self;

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
     * Add relationships to include with the results.
     *
     * @param array|null $relationships [optional] The relationships to get
     *
     * @return static
     */
    public function withRelationships(array $relationships = null): self;

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
}
