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

/**
 * Interface QueryBuilder.
 *
 * @author Melech Mizrachi
 */
interface QueryBuilder
{
    /**
     * Create a SELECT query statement.
     *
     * <code>
     *      $queryBuilder->select();
     *      $queryBuilder->select(
     *          [
     *              'column1',
     *              'column2',
     *              ...
     *          ]
     *      );
     * </code>
     *
     * @param array|null $columns
     *
     * @return static
     */
    public function select(array $columns = null): self;

    /**
     * Create an INSERT query statement.
     *
     * <code>
     *      $queryBuilder->insert();
     * </code>
     *
     * @return static
     */
    public function insert(): self;

    /**
     * Create an UPDATE query statement.
     *
     * <code>
     *      $queryBuilder->update();
     * </code>
     *
     * @return static
     */
    public function update(): self;

    /**
     * Create an DELETE query statement.
     *
     * <code>
     *      $queryBuilder->delete();
     * </code>
     *
     * @return static
     */
    public function delete(): self;

    /**
     * Set the table on which to perform the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table');
     *      $queryBuilder
     *          ->select()
     *          ->table('table', 't');
     * </code>
     *
     * @param string      $table
     * @param string|null $alias
     *
     * @return static
     */
    public function table(string $table, string $alias = null): self;

    /**
     * Set the entity to query with.
     *
     * <code>
     *      $queryBuilder->entity(Entity::class);
     * </code>
     *
     * @param string      $entity
     * @param string|null $alias
     *
     * @return static
     */
    public function entity(string $entity, string $alias = null): self;

    /**
     * Add a value for a column to set.
     *
     * <code>
     *      $queryBuilder
     *          ->insert()
     *          ->table('table')
     *          ->set('column', ':column');
     *      $queryBuilder
     *          ->update()
     *          ->table('table')
     *          ->set('column', ':column');
     * </code>
     *
     * @param string     $column
     * @param mixed|null $value
     *
     * @return static
     */
    public function set(string $column, $value = null): self;

    /**
     * Add a where condition to the query statement.
     * - Each additional use will add an `AND` where condition.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column');
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->where('column2', '=', ':column2');
     * </code>
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function where(string $column, string $operator = null, $value = null): self;

    /**
     * Add an additional `OR` where condition to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->orWhere('column2', '=', ':column2');
     * </code>
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function orWhere(string $column, string $operator = null, $value = null): self;

    /**
     * Add an groupBy by to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->groupBy('column');
     * </code>
     *
     * @param string      $column
     *
     * @return static
     */
    public function groupBy(string $column): self;

    /**
     * Add an order by to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->orderBy('column');
     * </code>
     *
     * @param string      $column
     * @param string|null $type [optional]
     *
     * @return static
     */
    public function orderBy(string $column, string $type = null): self;

    /**
     * Add an order by ascending to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->orderByAsc('column');
     * </code>
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByAsc(string $column): self;

    /**
     * Add an order by descending to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->orderByDesc('column');
     * </code>
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByDesc(string $column): self;

    /**
     * Add limit to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->limit(1);
     * </code>
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): self;

    /**
     * Add offset to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->offset(1);
     * </code>
     *
     * @param int $offset
     *
     * @return static
     */
    public function offset(int $offset): self;

    /**
     * Get the built query string.
     *
     * @return string
     */
    public function getQueryString(): string;

    /**
     * Create a new query.
     *
     * @return Query
     */
    public function createQuery(): Query;
}
