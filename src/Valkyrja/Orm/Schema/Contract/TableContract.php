<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Schema\Contract;

interface TableContract
{
    /**
     * Create the table.
     */
    public function create(): static;

    /**
     * Rename the table.
     *
     * @param string $name The new name
     */
    public function rename(string $name): static;

    /**
     * Drop the table.
     */
    public function drop(): static;

    /**
     * Set the name of the table.
     *
     * @param string $name The table name
     */
    public function setName(string $name): static;

    /**
     * Do only if the table doesn't already exist.
     */
    public function ifNotExists(): static;

    /**
     * Do only if the table exists.
     */
    public function ifExists(): static;

    /**
     * Create a new column.
     *
     * @param string $name The column name
     */
    public function createColumn(string $name): ColumnContract;

    /**
     * Change an existing column.
     *
     * @param string $name The column name
     */
    public function changeColumn(string $name): ColumnContract;

    /**
     * Drop an existing column.
     *
     * @param string $name The column name
     */
    public function dropColumn(string $name): ColumnContract;

    /**
     * Create a new index.
     *
     * @param string $name The index name
     */
    public function createIndex(string $name): IndexContract;

    /**
     * Change an existing index.
     *
     * @param string $name The index name
     */
    public function changeIndex(string $name): IndexContract;

    /**
     * Drop an existing index.
     *
     * @param string $name The index name
     */
    public function dropIndex(string $name): IndexContract;

    /**
     * Create a new constraint.
     *
     * @param string $name The constraint name
     */
    public function createConstraint(string $name): ConstraintContract;

    /**
     * Change an existing constraint.
     *
     * @param string $name The constraint name
     */
    public function changeConstraint(string $name): ConstraintContract;

    /**
     * Drop an existing constraint.
     *
     * @param string $name The constraint name
     */
    public function dropConstraint(string $name): ConstraintContract;

    /**
     * Get the built query string.
     */
    public function getQueryString(): string;
}
