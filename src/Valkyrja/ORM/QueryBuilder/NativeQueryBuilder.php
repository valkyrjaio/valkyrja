<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\QueryBuilder;

use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Enums\Statement;
use Valkyrja\ORM\Exceptions\WhereException;
use Valkyrja\ORM\QueryBuilder;

/**
 * Class QueryBuilder.
 */
class NativeQueryBuilder implements QueryBuilder
{
    /**
     * The type of statement to build.
     *
     * @var string
     */
    protected $type;

    /**
     * The columns for use in a select statement.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * The table upon which the statement executes.
     *
     * @var string
     */
    protected $table;

    /**
     * Where conditions for the query statement.
     *
     * @var array
     */
    protected $where = [];

    /**
     * Values to use for update/insert statements.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Order by conditions for the query statement.
     *
     * @var array
     */
    protected $orderBy = [];

    /**
     * Group by conditions for the query statement.
     *
     * @var array
     */
    protected $groupBy = [];

    /**
     * Limit condition for the query statement.
     *
     * @var int
     */
    protected $limit;

    /**
     * Offset condition for the query statement.
     *
     * @var int
     */
    protected $offset;

    /**
     * The built query.
     *
     * @var string
     */
    protected $query;

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
    public function select(array $columns = null): QueryBuilder
    {
        $this->type = Statement::SELECT;

        if (null !== $columns) {
            $this->columns = $columns;
        } else {
            $this->columns[] = '*';
        }

        return $this;
    }

    /**
     * Create an INSERT query statement.
     *
     * <code>
     *      $queryBuilder->insert();
     * </code>
     *
     * @return static
     */
    public function insert(): QueryBuilder
    {
        $this->type = Statement::INSERT;

        return $this;
    }

    /**
     * Create an UPDATE query statement.
     *
     * <code>
     *      $queryBuilder->update();
     * </code>
     *
     * @return static
     */
    public function update(): QueryBuilder
    {
        $this->type = Statement::UPDATE;

        return $this;
    }

    /**
     * Create an DELETE query statement.
     *
     * <code>
     *      $queryBuilder->delete();
     * </code>
     *
     * @return static
     */
    public function delete(): QueryBuilder
    {
        $this->type = Statement::DELETE;

        return $this;
    }

    /**
     * Set the table on which to perform the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table');
     *
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
    public function table(string $table, string $alias = null): QueryBuilder
    {
        $this->table = $table . ($alias !== null ? ' ' . $alias : '');

        return $this;
    }

    /**
     * Add a value for a column to set.
     *
     * <code>
     *      $queryBuilder
     *          ->insert()
     *          ->table('table')
     *          ->set('column', ':column');
     *
     *      $queryBuilder
     *          ->update()
     *          ->table('table')
     *          ->set('column', ':column');
     * </code>
     *
     * @param string $column
     * @param string $value
     *
     * @return static
     */
    public function set(string $column, string $value): QueryBuilder
    {
        $this->values[$column] = $value;

        return $this;
    }

    /**
     * Add a where condition to the query statement.
     * - Each additional use will add an `AND` where condition.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column');
     *
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->where('column2 = :column2');
     * </code>
     *
     * @param string $where
     *
     * @return static
     */
    public function where(string $where): QueryBuilder
    {
        if (empty($this->where)) {
            $this->setWhere($where);

            return $this;
        }

        $this->setWhere($where, Statement::WHERE_AND);

        return $this;
    }

    /**
     * Add an additional `AND` where condition to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->andWhere('column2 = :column2');
     * </code>
     *
     * @param string $where
     *
     * @throws WhereException If used without a where method before it
     *
     * @return static
     */
    public function andWhere(string $where): QueryBuilder
    {
        if (empty($this->where)) {
            throw new WhereException('Must use where method for first where condition.');
        }

        $this->setWhere($where, Statement::WHERE_AND);

        return $this;
    }

    /**
     * Add an additional `OR` where condition to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->andWhere('column2 = :column2');
     * </code>
     *
     * @param string $where
     *
     * @throws WhereException If used without a where method before it
     *
     * @return static
     */
    public function orWhere(string $where): QueryBuilder
    {
        if (empty($this->where)) {
            throw new WhereException('Must use where method for first where condition.');
        }

        $this->setWhere($where, Statement::WHERE_OR);

        return $this;
    }

    /**
     * Add an order by without specifying the order to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->orderBy('column');
     * </code>
     *
     * @param string $column
     *
     * @return static
     */
    public function orderBy(string $column): QueryBuilder
    {
        $this->setOrderBy($column);

        return $this;
    }

    /**
     * Add an order by ascending to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->orderByAsc('column');
     * </code>
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByAsc(string $column): QueryBuilder
    {
        $this->setOrderBy($column, OrderBy::ASC);

        return $this;
    }

    /**
     * Add an order by descending to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->orderByDesc('column');
     * </code>
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByDesc(string $column): QueryBuilder
    {
        $this->setOrderBy($column, OrderBy::DESC);

        return $this;
    }

    /**
     * Add limit to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->limit(1);
     * </code>
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): QueryBuilder
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Add offset to the query statement.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column = :column')
     *          ->offset(1);
     * </code>
     *
     * @param int $offset
     *
     * @return static
     */
    public function offset(int $offset): QueryBuilder
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get the built query string.
     *
     * @return string
     */
    public function getQuery(): string
    {
        if (null !== $this->query) {
            return $this->query;
        }

        switch ($this->type) {
            case Statement::SELECT :
                $this->query = $this->getSelectQuery();
                break;
            case Statement::UPDATE :
                $this->query = $this->getUpdateQuery();
                break;
            case Statement::INSERT :
                $this->query = $this->getInsertQuery();
                break;
            case Statement::DELETE :
                $this->query = $this->getDeleteQuery();
                break;
        }

        return $this->query;
    }

    /**
     * Set a where condition.
     *
     * @param string      $where
     * @param string|null $type
     *
     * @return void
     */
    protected function setWhere(string $where, string $type = null): void
    {
        if (null !== $type) {
            $where = $type . ' ' . $where;
        }

        $this->where[] = $where;
    }

    /**
     * Set an order by condition.
     *
     * @param string      $column
     * @param string|null $order
     *
     * @return void
     */
    protected function setOrderBy(string $column, string $order = null): void
    {
        $orderBy = $column;

        if (null !== $order) {
            $orderBy .= ' ' . $order;
        }

        $this->orderBy[] = $orderBy;
    }

    /**
     * Get a SELECT query.
     *
     * @return string
     */
    protected function getSelectQuery(): string
    {
        return $this->type
            . ' ' . implode(', ', $this->columns)
            . ' ' . Statement::FROM
            . ' ' . $this->table
            . ' ' . $this->getWhereQuery()
            . ' ' . $this->getOrderByQuery()
            . ' ' . $this->getLimitQuery()
            . ' ' . $this->getOffsetQuery();
    }

    /**
     * Get an INSERT query.
     *
     * @return string
     */
    protected function getInsertQuery(): string
    {
        return $this->type
            . ' ' . Statement::INTO
            . ' ' . $this->table
            . ' (' . implode(', ', array_keys($this->values)) . ')'
            . ' ' . Statement::VALUE
            . ' (' . implode(', ', $this->values) . ')'
            ;
    }

    /**
     * Get an UPDATE query.
     *
     * @return string
     */
    protected function getUpdateQuery(): string
    {
        return $this->type
            . ' ' . $this->table
            . ' ' . $this->getSetQuery()
            . ' ' . $this->getWhereQuery()
            . ' ' . $this->getOrderByQuery()
            . ' ' . $this->getLimitQuery();
    }

    /**
     * Get an DELETE query.
     *
     * @return string
     */
    protected function getDeleteQuery(): string
    {
        return $this->type
            . ' ' . Statement::FROM
            . ' ' . $this->table
            . ' ' . $this->getWhereQuery()
            . ' ' . $this->getOrderByQuery()
            . ' ' . $this->getLimitQuery();
    }

    /**
     * Get the SET part of an INSERT query.
     *
     * @return string
     */
    protected function getSetQuery(): string
    {
        $query = '';

        foreach ($this->values as $column => $value) {
            if (! empty($query)) {
                $query .= ',';
            }

            $query .= ', ' . $column . ' = ' . $value;
        }

        return Statement::SET . ' ' . $query;
    }

    /**
     * Get the WHERE part of a query statement.
     *
     * @return string
     */
    protected function getWhereQuery(): string
    {
        if (empty($this->where)) {
            return '';
        }

        return Statement::WHERE . ' ' . implode(' ', $this->where);
    }

    /**
     * Get the ORDER BY part of a query statement.
     *
     * @return string
     */
    protected function getOrderByQuery(): string
    {
        if (empty($this->orderBy)) {
            return '';
        }

        return Statement::ORDER_BY . ' ' . implode(', ', $this->orderBy);
    }

    /**
     * Get the LIMIT part of a query statement.
     *
     * @return string
     */
    protected function getLimitQuery(): string
    {
        if (null === $this->limit) {
            return '';
        }

        return Statement::LIMIT . ' ' . $this->limit;
    }

    /**
     * Get the OFFSET part of a query statement.
     *
     * @return string
     */
    protected function getOffsetQuery(): string
    {
        if (null === $this->offset) {
            return '';
        }

        return Statement::OFFSET . ' ' . $this->offset;
    }
}
