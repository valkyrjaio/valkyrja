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

namespace Valkyrja\Orm\Schema\Contract;

/**
 * Interface TableContract.
 */
interface TableContract
{
    /**
     * Create the table.
     *
     * @return static
     */
    public function create(): static;

    /**
     * Rename the table.
     *
     * @param string $name The new name
     *
     * @return static
     */
    public function rename(string $name): static;

    /**
     * Drop the table.
     *
     * @return static
     */
    public function drop(): static;

    /**
     * Set the name of the table.
     *
     * @param string $name The table name
     *
     * @return static
     */
    public function setName(string $name): static;

    /**
     * Do only if the table doesn't already exist.
     *
     * @return static
     */
    public function ifNotExists(): static;

    /**
     * Do only if the table exists.
     *
     * @return static
     */
    public function ifExists(): static;

    /**
     * Create a new column.
     *
     * @param string $name The column name
     *
     * @return ColumnContract
     */
    public function createColumn(string $name): ColumnContract;

    /**
     * Change an existing column.
     *
     * @param string $name The column name
     *
     * @return ColumnContract
     */
    public function changeColumn(string $name): ColumnContract;

    /**
     * Drop an existing column.
     *
     * @param string $name The column name
     *
     * @return ColumnContract
     */
    public function dropColumn(string $name): ColumnContract;

    /**
     * Create a new index.
     *
     * @param string $name The index name
     *
     * @return IndexContract
     */
    public function createIndex(string $name): IndexContract;

    /**
     * Change an existing index.
     *
     * @param string $name The index name
     *
     * @return IndexContract
     */
    public function changeIndex(string $name): IndexContract;

    /**
     * Drop an existing index.
     *
     * @param string $name The index name
     *
     * @return IndexContract
     */
    public function dropIndex(string $name): IndexContract;

    /**
     * Create a new constraint.
     *
     * @param string $name The constraint name
     *
     * @return ConstraintContract
     */
    public function createConstraint(string $name): ConstraintContract;

    /**
     * Change an existing constraint.
     *
     * @param string $name The constraint name
     *
     * @return ConstraintContract
     */
    public function changeConstraint(string $name): ConstraintContract;

    /**
     * Drop an existing constraint.
     *
     * @param string $name The constraint name
     *
     * @return ConstraintContract
     */
    public function dropConstraint(string $name): ConstraintContract;

    /**
     * Get the built query string.
     *
     * @return string
     */
    public function getQueryString(): string;
}
