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

namespace Valkyrja\Orm\QueryBuilder;

use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder;

/**
 * Class SqlUpdateQueryBuilder.
 *
 * @author Melech Mizrachi
 */
class SqlUpdateQueryBuilder extends SqlQueryBuilder implements UpdateQueryBuilder
{
    /** @var Value[] */
    protected array $values = [];

    /**
     * @inheritDoc
     */
    public function withSet(Value ...$values): static
    {
        $new = clone $this;

        $new->values = $values;

        return $new;
    }

    /**
     * @inheritDoc
     */
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
     *
     * @return string
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
