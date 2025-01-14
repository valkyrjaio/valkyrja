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

namespace Valkyrja\Orm\QueryBuilder\Contract;

/**
 * Interface QueryBuilder.
 *
 * @author Melech Mizrachi
 */
interface QueryBuilder extends BaseQueryBuilder, WhereQueryBuilder
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
     * @param string[]|null $columns
     *
     * @return static
     */
    public function select(array|null $columns = null): static;

    /**
     * Create an INSERT query statement.
     *
     * <code>
     *      $queryBuilder->insert();
     * </code>
     *
     * @return static
     */
    public function insert(): static;

    /**
     * Create an UPDATE query statement.
     *
     * <code>
     *      $queryBuilder->update();
     * </code>
     *
     * @return static
     */
    public function update(): static;

    /**
     * Create an DELETE query statement.
     *
     * <code>
     *      $queryBuilder->delete();
     * </code>
     *
     * @return static
     */
    public function delete(): static;

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
    public function set(string $column, mixed $value = null): static;

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
        string|null $operator = null,
        string|null $type = null,
        bool|null $isWhere = null
    ): static;

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
     * @param string $column
     *
     * @return static
     */
    public function groupBy(string $column): static;

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
     * @param string|null $type   [optional]
     *
     * @return static
     */
    public function orderBy(string $column, string|null $type = null): static;

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
    public function orderByAsc(string $column): static;

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
    public function orderByDesc(string $column): static;

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
    public function limit(int $limit): static;

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
    public function offset(int $offset): static;
}
