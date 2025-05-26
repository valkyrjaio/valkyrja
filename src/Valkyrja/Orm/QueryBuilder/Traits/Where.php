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

namespace Valkyrja\Orm\QueryBuilder\Traits;

use Valkyrja\Orm\Constant\Operator;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Support\Helpers;

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
     * @var string[]
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
     *
     * @return static
     */
    public function where(
        string $column,
        string|null $operator = null,
        QueryBuilder|array|string|float|int|bool|null $value = null,
        bool $setType = true
    ): static {
        $this->setWhere(
            $this->getWhereString($column, $operator ?? Operator::EQUALS, $value),
            $setType ? Statement::WHERE_AND : ''
        );

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function startWhereGroup(): static
    {
        $this->whereGroupStarted = true;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function endWhereGroup(): static
    {
        $this->where[] = ')';

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function whereType(WhereType $type = WhereType::AND): static
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
     * Get a where value.
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
