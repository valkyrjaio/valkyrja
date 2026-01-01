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

namespace Valkyrja\Orm\Data;

use Override;
use Stringable;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\JoinType;

readonly class Join implements Stringable
{
    /**
     * @param non-empty-string $table      The join table
     * @param non-empty-string $column     The column to compare
     * @param non-empty-string $joinColumn The join table column
     */
    public function __construct(
        public string $table,
        public string $column,
        public string $joinColumn,
        public Comparison $comparison,
        public JoinOperator $operator,
        public JoinType $type = JoinType::DEFAULT,
    ) {
    }

    /**
     * Get the join as a string.
     *
     * @return non-empty-string
     */
    #[Override]
    public function __toString(): string
    {
        $type       = $this->type->value;
        $join       = Statement::JOIN;
        $table      = $this->table;
        $operator   = $this->operator->value;
        $comparison = $this->comparison->value;
        $column     = $this->column;
        $joinColumn = $this->joinColumn;

        return "$type $join $table $operator $column $comparison $joinColumn";
    }
}
