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

namespace Valkyrja\Orm\QueryBuilders;

use Valkyrja\Orm\Constants\OrderBy;
use Valkyrja\Orm\Constants\Statement;
use Valkyrja\Orm\QueryBuilders\Traits\Join;
use Valkyrja\Orm\QueryBuilders\Traits\Where;
use Valkyrja\Orm\SelectQueryBuilder as Contract;

use function implode;

/**
 * Class SqlSelectQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlSelectQueryBuilder extends SqlBaseQueryBuilder implements Contract
{
    use Join;
    use Where;

    /**
     * The columns for use in a select statement.
     *
     * @var array
     */
    protected array $columns = [];

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
     * @inheritDoc
     */
    public function columns(array $columns = null): self
    {
        $this->columns = $columns ?? ['*'];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): self
    {
        $this->groupBy[] = $column;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string $type = null): self
    {
        $this->orderBy[] = $column . ' ' . ((string) $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderByAsc(string $column): self
    {
        return $this->orderBy($column, OrderBy::ASC);
    }

    /**
     * @inheritDoc
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, OrderBy::DESC);
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQueryString(): string
    {
        return Statement::SELECT
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
