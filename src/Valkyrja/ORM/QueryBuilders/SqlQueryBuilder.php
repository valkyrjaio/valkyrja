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

namespace Valkyrja\ORM\QueryBuilders;

use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Constants\JoinType;
use Valkyrja\ORM\Constants\Operator;
use Valkyrja\ORM\Constants\OrderBy;
use Valkyrja\ORM\Constants\Statement;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Support\Helpers;

use function array_keys;
use function implode;
use function is_array;

/**
 * Class SqlQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlQueryBuilder implements QueryBuilder
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The type of statement to build.
     *
     * @var string
     */
    protected string $type;

    /**
     * The columns for use in a select statement.
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * The table upon which the statement executes.
     *
     * @var string
     */
    protected string $table;

    /**
     * Where conditions for the query statement.
     *
     * @var array
     */
    protected array $where = [];

    /**
     * Values to use for update/insert statements.
     *
     * @var array
     */
    protected array $values = [];

    /**
     * Joins for the query statement.
     *
     * @var array
     */
    protected array $joins = [];

    /**
     * Order by conditions for the query statement.
     *
     * @var array
     */
    protected array $orderBy = [];

    /**
     * Group by conditions for the query statement.
     *
     * @var array
     */
    protected array $groupBy = [];

    /**
     * Limit condition for the query statement.
     *
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * Offset condition for the query statement.
     *
     * @var int|null
     */
    protected ?int $offset = null;

    /**
     * The entity to query with.
     *
     * @var Entity|string|null
     */
    protected ?string $entity = null;

    /**
     * SqlQueryBuilder constructor.
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     */
    public function select(array $columns = null): static
    {
        $this->type    = Statement::SELECT;
        $this->columns = $columns ?? ['*'];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function insert(): static
    {
        $this->type = Statement::INSERT;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function update(): static
    {
        $this->type = Statement::UPDATE;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(): static
    {
        $this->type = Statement::DELETE;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function table(string $table, string $alias = null): static
    {
        $this->table = $table . ' ' . ((string) $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function entity(string $entity, string $alias = null): static
    {
        $this->entity = $entity;

        $this->table($entity, $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set(string $column, $value = null): static
    {
        $this->values[$column] = $value ?? ":$column";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, $value = null): static
    {
        $this->setWhere($this->getWhereString($column, $operator ?? Operator::EQUALS, $value), Statement::WHERE_AND);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhere(string $column, string $operator = null, $value = null): static
    {
        $this->setWhere($this->getWhereString($column, $operator ?? Operator::EQUALS, $value), Statement::WHERE_OR);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join(
        string $table,
        string $column1,
        string $column2,
        string $operator = null,
        string $type = null,
        bool $isWhere = null
    ): static {
        // The operator defaulting to =
        $operator ??= Operator::EQUALS;
        // WHERE or ON for the join
        $statementType = $isWhere ? Statement::WHERE : Statement::ON;
        // Get the type defaulting to inner
        $type ??= JoinType::INNER;
        // Get the join wording
        $join = Statement::JOIN;

        $this->joins[] = "{$type} {$join} {$table} {$statementType} {$column1} {$operator} {$column2}";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): static
    {
        $this->groupBy[] = $column;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string $type = null): static
    {
        $this->orderBy[] = $column . ' ' . ((string) $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderByAsc(string $column): static
    {
        return $this->orderBy($column, OrderBy::ASC);
    }

    /**
     * @inheritDoc
     */
    public function orderByDesc(string $column): static
    {
        return $this->orderBy($column, OrderBy::DESC);
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQueryString(): string
    {
        $queryString = '';

        switch ($this->type) {
            case Statement::SELECT:
                $queryString = $this->getSelectQuery();

                break;
            case Statement::UPDATE:
                $queryString = $this->getUpdateQuery();

                break;
            case Statement::INSERT:
                $queryString = $this->getInsertQuery();

                break;
            case Statement::DELETE:
                $queryString = $this->getDeleteQuery();

                break;
        }

        return $queryString;
    }

    /**
     * @inheritDoc
     */
    public function createQuery(): Query
    {
        return $this->adapter->createQuery($this->getQueryString(), $this->entity);
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
        $this->where[] = (empty($this->where) ? '' : (string) $type) . ' ' . $where;
    }

    /**
     * Get a where string.
     *
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return string
     */
    protected function getWhereString(string $column, string $operator, mixed $value = null): string
    {
        return $column . ' ' . $operator . ' ' . $this->getWhereValue($column, $value);
    }

    /**
     * Get a where value
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return string
     */
    protected function getWhereValue(string $column, mixed $value): string
    {
        if (null === $value) {
            return Helpers::getColumnForValueBind($column);
        }

        if (! is_array($value)) {
            return (string) $value;
        }

        $columnValueBind = Helpers::getColumnForValueBind($column);

        return '(' . $columnValueBind . implode(', ' . $columnValueBind, array_keys($value)) . ')';
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
            . ' ' . $this->getJoinQuery()
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
            . ' ' . Statement::VALUES
            . ' (' . implode(', ', $this->values) . ')';
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
        $values = [];

        foreach ($this->values as $column => $value) {
            $values[] = $column . ' = ' . $value;
        }

        return Statement::SET . ' ' . implode(', ', $values);
    }

    /**
     * Get the JOINs of a query statement.
     *
     * @return string
     */
    protected function getJoinQuery(): string
    {
        return empty($this->joins)
            ? ''
            : ' ' . implode(' ', $this->joins);
    }

    /**
     * Get the WHERE part of a query statement.
     *
     * @return string
     */
    protected function getWhereQuery(): string
    {
        return empty($this->where)
            ? ''
            : Statement::WHERE . ' ' . implode(' ', $this->where);
    }

    /**
     * Get the GROUP BY part of a query statement.
     *
     * @return string
     */
    protected function getGroupByQuery(): string
    {
        return empty($this->orderBy) || $this->isCount()
            ? ''
            : Statement::GROUP_BY . ' ' . implode(', ', $this->groupBy);
    }

    /**
     * Get the ORDER BY part of a query statement.
     *
     * @return string
     */
    protected function getOrderByQuery(): string
    {
        return empty($this->orderBy) || $this->isCount()
            ? ''
            : Statement::ORDER_BY . ' ' . implode(', ', $this->orderBy);
    }

    /**
     * Get the LIMIT part of a query statement.
     *
     * @return string
     */
    protected function getLimitQuery(): string
    {
        return null === $this->limit || $this->isCount()
            ? ''
            : Statement::LIMIT . ' ' . $this->limit;
    }

    /**
     * Get the OFFSET part of a query statement.
     *
     * @return string
     */
    protected function getOffsetQuery(): string
    {
        return null === $this->offset || $this->isCount()
            ? ''
            : Statement::OFFSET . ' ' . $this->offset;
    }

    /**
     * Determine whether this is a count statement.
     *
     * @return bool
     */
    protected function isCount(): bool
    {
        return $this->columns[0] === Statement::COUNT_ALL;
    }
}
