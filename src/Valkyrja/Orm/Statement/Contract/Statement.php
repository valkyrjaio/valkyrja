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

namespace Valkyrja\Orm\Statement\Contract;

use Valkyrja\Orm\Data\Value;

/**
 * Interface Statement.
 *
 * @author Melech Mizrachi
 */
interface Statement
{
    /**
     * Bind a value.
     *
     * @param Value $value The value to bind
     *
     * @return bool
     */
    public function bindValue(Value $value): bool;

    /**
     * Execute the statement.
     *
     * @return bool
     */
    public function execute(): bool;

    /**
     * Get a column's meta information.
     *
     * @param int $columnNumber The column index in relation to the query statement
     *
     * @return array<string, mixed>
     */
    public function getColumnMeta(int $columnNumber): array;

    /**
     * Fetch the results.
     *
     * @return array<string, mixed>
     */
    public function fetch(): array;

    /**
     * Fetch a single column.
     *
     * @param int $columnNumber
     *
     * @return mixed
     */
    public function fetchColumn(int $columnNumber = 0): mixed;

    /**
     * Fetch all the results.
     *
     * @return array<string, mixed>[]
     */
    public function fetchAll(): array;

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * The number of rows returned.
     *
     * @return int
     */
    public function rowCount(): int;

    /**
     * Count of columns returned.
     *
     * @return int
     */
    public function columnCount(): int;

    /**
     * The error code.
     *
     * @return string
     */
    public function errorCode(): string;

    /**
     * The error message.
     *
     * @return string|null
     */
    public function errorMessage(): string|null;
}
