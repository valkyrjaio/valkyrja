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
use Valkyrja\Orm\Entity\Contract\EntityContract;

interface StatementContract
{
    /**
     * Bind a value.
     *
     * @param Value $value The value to bind
     */
    public function bindValue(Value $value): bool;

    /**
     * Execute the statement.
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
     * Fetch the results as a given entity.
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity class name
     *
     * @return T
     */
    public function fetchEntity(string $entity): EntityContract;

    /**
     * Fetch a single column.
     */
    public function fetchColumn(int $columnNumber = 0): mixed;

    /**
     * Fetch all the results.
     *
     * @return array<string, mixed>[]
     */
    public function fetchAll(): array;

    /**
     * Fetch all the results as an array of a given entity.
     *
     * @template T of EntityContract
     *
     * @param class-string<T> $entity The entity class name
     *
     * @return T[]
     */
    public function fetchAllEntities(string $entity): array;

    /**
     * Get the count.
     */
    public function getCount(): int;

    /**
     * The number of rows returned.
     */
    public function getRowCount(): int;

    /**
     * Count of columns returned.
     */
    public function getColumnCount(): int;

    /**
     * Determine if there is an error.
     */
    public function hasError(): bool;

    /**
     * The error code.
     */
    public function getErrorCode(): string;

    /**
     * The error message.
     */
    public function getErrorMessage(): string;
}
