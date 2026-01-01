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

namespace Valkyrja\Orm\QueryBuilder\Abstract;

use Override;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\WhereGroup;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract as Contract;

abstract class SqlQueryBuilder implements Contract
{
    /** @var string */
    protected string $alias = '';
    /** @var Join[] */
    protected array $joins = [];
    /** @var array<Where|WhereGroup> */
    protected array $where = [];

    /**
     * @param non-empty-string $from The table
     */
    public function __construct(
        protected string $from,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFrom(string $table): static
    {
        $new = clone $this;

        $new->from = $table;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAlias(string $alias): static
    {
        $new = clone $this;

        $new->alias = $alias;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withJoin(Join ...$joins): static
    {
        $new = clone $this;

        $new->joins = $joins;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedJoin(Join ...$joins): static
    {
        $new = clone $this;

        $new->joins = array_merge($new->joins, $joins);

        return $new;
    }

    #[Override]
    public function withWhere(Where|WhereGroup ...$where): static
    {
        $new = clone $this;

        $new->where = $where;

        return $new;
    }

    #[Override]
    public function withAddedWhere(Where|WhereGroup ...$where): static
    {
        $new = clone $this;

        $new->where = array_merge($new->where, $where);

        return $new;
    }

    /**
     * Get the alias of a query statement.
     *
     * @return string
     */
    protected function getAliasQuery(): string
    {
        return $this->alias === ''
            ? ''
            : " $this->alias";
    }

    /**
     * Get the where of a query statement.
     *
     * @return string
     */
    protected function getWhereQuery(): string
    {
        return empty($this->where)
            ? ''
            : ' ' . Statement::WHERE . ' ' . implode(' ', $this->where);
    }

    /**
     * Get the joins of a query statement.
     *
     * @return string
     */
    protected function getJoinQuery(): string
    {
        return empty($this->joins)
            ? ''
            : ' ' . implode(' ', $this->joins);
    }
}
