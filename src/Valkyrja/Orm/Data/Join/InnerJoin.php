<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data\Join;

use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;

readonly class InnerJoin extends Join
{
    /**
     * @param non-empty-string $table      The join table
     * @param non-empty-string $column     The column to compare
     * @param non-empty-string $joinColumn The join table column
     */
    public function __construct(
        string $table,
        string $column,
        string $joinColumn,
        Comparison $comparison,
        JoinOperator $operator,
    ) {
        parent::__construct(
            table: $table,
            column: $column,
            joinColumn: $joinColumn,
            comparison: $comparison,
            operator: $operator,
            type: JoinType::INNER,
        );
    }
}
