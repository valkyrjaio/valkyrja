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

use Stringable;
use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\WhereGroup;

/**
 * Interface QueryBuilder.
 *
 * @author Melech Mizrachi
 */
interface QueryBuilder extends Stringable
{
    /**
     * Create a new query builder with the specified table.
     *
     * @param non-empty-string $table The table to query from
     *
     * @return static
     */
    public function withFrom(string $table): static;

    /**
     * Create a new query builder with the specified alias.
     *
     * @param non-empty-string $alias The alias for the table
     *
     * @return static
     */
    public function withAlias(string $alias): static;

    /**
     * Create a new query builder with the specified join clauses.
     *
     * @param Join ...$joins The join clauses
     *
     * @return static
     */
    public function withJoin(Join ...$joins): static;

    /**
     * Create a new query builder with the added join clauses.
     *
     * @param Join ...$joins The join clauses
     *
     * @return static
     */
    public function withAddedJoin(Join ...$joins): static;

    /**
     * Create a new query builder with the specified where clauses.
     *
     * @param Where|WhereGroup ...$where The where clauses
     *
     * @return static
     */
    public function withWhere(Where|WhereGroup ...$where): static;

    /**
     * Create a new query builder with the added where clauses.
     *
     * @param Where|WhereGroup ...$where The where clauses
     *
     * @return static
     */
    public function withAddedWhere(Where|WhereGroup ...$where): static;

    /**
     * Get the builder as a query string.
     *
     * @return non-empty-string
     */
    public function __toString(): string;
}
