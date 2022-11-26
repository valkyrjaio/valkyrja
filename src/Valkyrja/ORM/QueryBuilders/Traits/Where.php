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
use Valkyrja\ORM\Enums\WhereType;
use Valkyrja\ORM\QueryBuilder;
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
     * Has a where group been started.
     *
     * @var bool
     */
    protected bool $whereGroupStarted = false;

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, mixed $value = null, bool $setType = true): self
    {
        $this->setWhere(
            $this->getWhereString($column, $operator ?? Operator::EQUALS, $value),
            $setType ? Statement::WHERE_AND : ''
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function startWhereGroup(): self
    {
        $this->whereGroupStarted = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function endWhereGroup(): self
    {
        $this->where[] = ')';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereType(WhereType $type = WhereType::AND): self
    {
        $this->where[] = $type->value;

        return $this;
    }

    /**
     * Set a where condition.
     *
     * @param string $where
     * @param string $type
     *
     * @return void
     */
    protected function setWhere(string $where, string $type): void
    {
        $this->where[] = (empty($this->where) ? '' : $type)
            . ' '
            . ($this->whereGroupStarted ? '(' : '')
            . $where;

        $this->whereGroupStarted = false;
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
        $columnValueBind = Helpers::getColumnForValueBind($column);

        if ($value instanceof QueryBuilder) {
            return '(' . $value->getQueryString() . ')';
        }

        if (! is_array($value)) {
            return $columnValueBind;
        }

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
