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

namespace Valkyrja\Orm\Data\Join;

use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;

readonly class RightJoin extends Join
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
            type: JoinType::RIGHT,
        );
    }
}
