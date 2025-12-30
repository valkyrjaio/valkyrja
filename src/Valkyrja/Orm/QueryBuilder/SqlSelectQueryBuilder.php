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

namespace Valkyrja\Orm\QueryBuilder;

use Override;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Data\OrderBy;
use Valkyrja\Orm\QueryBuilder\Abstract\SqlQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilder as Contract;

/**
 * Class SqlSelectQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlSelectQueryBuilder extends SqlQueryBuilder implements Contract
{
    /** @var non-empty-string[] */
    protected array $columns = ['*'];
    /** @var string[] */
    protected array $groupBy = [];
    /** @var OrderBy[] */
    protected array $orderBy = [];
    /** @var int|null */
    protected int|null $limit = null;
    /** @var int|null */
    protected int|null $offset = null;

    /**
     * @inheritDoc
     */
    #[Override]
    public function withColumns(string ...$columns): static
    {
        $new = clone $this;

        $new->columns = $columns;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedColumns(string ...$columns): static
    {
        $new = clone $this;

        $new->columns = array_merge($new->columns, $columns);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withGroupBy(string ...$groupBy): static
    {
        $new = clone $this;

        $new->groupBy = $groupBy;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedGroupBy(string ...$groupBy): static
    {
        $new = clone $this;

        $new->groupBy = array_merge($new->groupBy, $groupBy);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withOrderBy(OrderBy ...$orderBy): static
    {
        $new = clone $this;

        $new->orderBy = $orderBy;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedOrderBy(OrderBy ...$orderBy): static
    {
        $new = clone $this;

        $new->orderBy = array_merge($new->orderBy, $orderBy);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withLimit(int $limit): static
    {
        $new = clone $this;

        $new->limit = $limit;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withOffset(int $offset): static
    {
        $new = clone $this;

        $new->offset = $offset;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return Statement::SELECT
            . ' ' . implode(', ', $this->columns)
            . ' ' . Statement::FROM
            . " $this->from"
            . $this->getJoinQuery()
            . $this->getWhereQuery()
            . $this->getGroupByQuery()
            . $this->getOrderByQuery()
            . $this->getLimitQuery()
            . $this->getOffsetQuery();
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
            : ' ' . Statement::GROUP_BY . ' ' . implode(', ', $this->groupBy);
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
            : ' ' . Statement::ORDER_BY . ' ' . implode(', ', $this->orderBy);
    }

    /**
     * Get the LIMIT part of a query statement.
     *
     * @return string
     */
    protected function getLimitQuery(): string
    {
        return $this->limit === null || $this->isCount()
            ? ''
            : ' ' . Statement::LIMIT . ' ' . ((string) $this->limit);
    }

    /**
     * Get the OFFSET part of a query statement.
     *
     * @return string
     */
    protected function getOffsetQuery(): string
    {
        return $this->offset === null || $this->isCount()
            ? ''
            : ' ' . Statement::OFFSET . ' ' . ((string) $this->offset);
    }

    /**
     * Determine whether this is a count statement.
     *
     * @return bool
     */
    protected function isCount(): bool
    {
        return str_starts_with($this->columns[0], 'COUNT');
    }
}
