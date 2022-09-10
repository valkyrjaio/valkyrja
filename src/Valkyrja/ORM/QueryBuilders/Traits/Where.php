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

namespace Valkyrja\ORM\QueryBuilders\Traits;

use Valkyrja\ORM\Constants\Operator;
use Valkyrja\ORM\Constants\Statement;
use Valkyrja\ORM\Support\Helpers;

use function array_keys;
use function implode;
use function is_array;

/**
 * Class Where.
 *
 * @author Melech Mizrachi
 */
trait Where
{
    /**
     * Where conditions for the query statement.
     *
     * @var array
     */
    protected array $where = [];

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, mixed $value = null): static
    {
        $this->setWhere($this->getWhereString($column, $operator ?? Operator::EQUALS, $value), Statement::WHERE_AND);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhere(string $column, string $operator = null, mixed $value = null): static
    {
        $this->setWhere($this->getWhereString($column, $operator ?? Operator::EQUALS, $value), Statement::WHERE_OR);

        return $this;
    }

    /**
     * Set a where condition.
     *
     * @param string      $where
     * @param string|null $type
     *
     * @return void
     */
    protected function setWhere(string $where, string $type = null): void
    {
        $this->where[] = (empty($this->where) ? '' : (string) $type) . ' ' . $where;
    }

    /**
     * Get a where string.
     *
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     *
     * @return string
     */
    protected function getWhereString(string $column, string $operator, mixed $value = null): string
    {
        return $column . ' ' . $operator . ' ' . $this->getWhereValue($column, $value);
    }

    /**
     * Get a where value
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return string
     */
    protected function getWhereValue(string $column, mixed $value): string
    {
        if (null === $value) {
            return Helpers::getColumnForValueBind($column);
        }

        if (! is_array($value)) {
            return (string) $value;
        }

        $columnValueBind = Helpers::getColumnForValueBind($column);

        return '(' . $columnValueBind . implode(', ' . $columnValueBind, array_keys($value)) . ')';
    }

    /**
     * Get the WHERE part of a query statement.
     *
     * @return string
     */
    protected function getWhereQuery(): string
    {
        return empty($this->where)
            ? ''
            : Statement::WHERE . ' ' . implode(' ', $this->where);
    }
}
