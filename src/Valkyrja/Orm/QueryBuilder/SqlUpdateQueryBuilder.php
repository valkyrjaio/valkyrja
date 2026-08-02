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
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilderContract;

use function array_merge;
use function implode;

class SqlUpdateQueryBuilder extends SqlQueryBuilder implements UpdateQueryBuilderContract
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
        return Statement::UPDATE
            . " $this->from"
            . $this->getAliasQuery()
            . $this->getSetQuery()
            . $this->getWhereQuery()
            . $this->getJoinQuery();
    }

    /**
     * Get the SET part of an INSERT query.
     */
    protected function getSetQuery(): string
    {
        $values = [];

        foreach ($this->values as $value) {
            $values[] = "$value->name = " . ((string) $value);
        }

        return Statement::SET . ' ' . implode(', ', $values);
    }
}
