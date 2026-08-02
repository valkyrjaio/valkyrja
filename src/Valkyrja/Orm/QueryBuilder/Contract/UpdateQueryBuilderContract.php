<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\QueryBuilder\Contract;

use Valkyrja\Orm\Data\Value;

interface UpdateQueryBuilderContract extends QueryBuilderContract
{
    /**
     * Create a new query builder with the specified values.
     *
     * @param Value ...$values The values
     */
    public function withSet(Value ...$values): static;

    /**
     * Create a new query builder with added values.
     *
     * @param Value ...$values The values
     */
    public function withAddedSet(Value ...$values): static;
}
