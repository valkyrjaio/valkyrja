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

use Valkyrja\Orm\Data\Value;

/**
 * Interface InsertQueryBuilderContract.
 */
interface InsertQueryBuilderContract extends QueryBuilderContract
{
    /**
     * Create a new query builder with the specified values.
     *
     * @param Value ...$values The values
     *
     * @return static
     */
    public function withSet(Value ...$values): static;

    /**
     * Create a new query builder with added values.
     *
     * @param Value ...$values The values
     *
     * @return static
     */
    public function withAddedSet(Value ...$values): static;
}
