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

use Valkyrja\Orm\Data\OrderBy;

interface SelectQueryBuilderContract extends QueryBuilderContract
{
    /**
     * Create a new query builder with the specified columns.
     *
     * @param non-empty-string ...$columns The columns
     */
    public function withColumns(string ...$columns): static;

    /**
     * Create a new query builder with the added columns.
     *
     * @param non-empty-string ...$columns The columns
     */
    public function withAddedColumns(string ...$columns): static;

    /**
     * Create a new query builder with the specified group by.
     *
     * @param string ...$groupBy The group by columns
     */
    public function withGroupBy(string ...$groupBy): static;

    /**
     * Create a new query builder with the added group by.
     *
     * @param string ...$groupBy The group by columns
     */
    public function withAddedGroupBy(string ...$groupBy): static;

    /**
     * Create a new query builder with the specified order by.
     *
     * @param OrderBy ...$orderBy The order by
     */
    public function withOrderBy(OrderBy ...$orderBy): static;

    /**
     * Create a new query builder with the added order by.
     *
     * @param OrderBy ...$orderBy The order by
     */
    public function withAddedOrderBy(OrderBy ...$orderBy): static;

    /**
     * Create a new query builder with the specified limit.
     *
     * @param int $limit The limit
     */
    public function withLimit(int $limit): static;

    /**
     * Create a new query builder with the specified offset.
     *
     * @param int $offset The offset
     */
    public function withOffset(int $offset): static;
}
