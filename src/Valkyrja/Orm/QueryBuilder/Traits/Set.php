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

use Valkyrja\Orm\Constant\Statement;

use function implode;

/**
 * Class Set.
 *
 * @author Melech Mizrachi
 */
trait Set
{
    /**
     * Values to use for update/insert statements.
     *
     * @var array<string, string|float|int|bool>
     */
    protected array $values = [];

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function set(string $column, string|float|int|bool|null $value = null): static
    {
        $this->values[$column] = $value ?? ":$column";

        return $this;
    }

    /**
     * Get the SET part of an INSERT query.
     *
     * @return string
     */
    protected function getSetQuery(): string
    {
        $values = [];

        foreach ($this->values as $column => $value) {
            $values[] = $column . ' = ' . ((string) $value);
        }

        return Statement::SET . ' ' . implode(', ', $values);
    }
}
