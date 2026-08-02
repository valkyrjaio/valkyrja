<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\QueryBuilder;

use Override;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\QueryBuilder\Abstract\SqlQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilderContract;

use function array_merge;
use function implode;

class SqlInsertQueryBuilder extends SqlQueryBuilder implements InsertQueryBuilderContract
{
    /** @var Value[] */
    protected array $values = [];

    /**
     * @inheritDoc
     */
    #[Override]
    public function withSet(Value ...$values): static
    {
        $new = clone $this;

        $new->values = $values;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedSet(Value ...$values): static
    {
        $new = clone $this;

        $new->values = array_merge($new->values, $values);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $query = Statement::INSERT
            . ' ' . Statement::INTO
            . " $this->from"
            . $this->getAliasQuery();

        $columns = [];
        $values  = [];

        foreach ($this->values as $value) {
            $columns[] = $value->name;
            $values[]  = (string) $value;
        }

        $columns = implode(', ', $columns);
        $values  = implode(', ', $values);

        return $query
            . " ($columns)"
            . ' ' . Statement::VALUES
            . " ($values)"
            . $this->getWhereQuery()
            . $this->getJoinQuery();
    }
}
