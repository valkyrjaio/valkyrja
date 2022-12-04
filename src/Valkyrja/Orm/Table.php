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

namespace Valkyrja\Orm;

/**
 * Interface Table.
 *
 * @author Melech Mizrachi
 */
interface Table
{
    /**
     * Create the table.
     *
     * @return static
     */
    public function create(): self;

    /**
     * Rename the table.
     *
     * @param string $name The new name
     *
     * @return static
     */
    public function rename(string $name): self;

    /**
     * Drop the table.
     *
     * @return static
     */
    public function drop(): self;

    /**
     * Set the name of the table.
     *
     * @param string $name The table name
     *
     * @return static
     */
    public function setName(string $name): self;

    /**
     * Do only if the table doesn't already exist.
     *
     * @return static
     */
    public function ifNotExists(): self;

    /**
     * Do only if the table exists.
     *
     * @return static
     */
    public function ifExists(): self;

    /**
     * Create a new column.
     *
     * @param string $name The column name
     *
     * @return Column
     */
    public function createColumn(string $name): Column;

    /**
     * Change an existing column.
     *
     * @param string $name The column name
     *
     * @return Column
     */
    public function changeColumn(string $name): Column;

    /**
     * Drop an existing column.
     *
     * @param string $name The column name
     *
     * @return Column
     */
    public function dropColumn(string $name): Column;

    /**
     * Create a new index.
     *
     * @param string $name The index name
     *
     * @return Index
     */
    public function createIndex(string $name): Index;

    /**
     * Change an existing index.
     *
     * @param string $name The index name
     *
     * @return Index
     */
    public function changeIndex(string $name): Index;

    /**
     * Drop an existing index.
     *
     * @param string $name The index name
     *
     * @return Index
     */
    public function dropIndex(string $name): Index;

    /**
     * Create a new constraint.
     *
     * @param string $name The constraint name
     *
     * @return Constraint
     */
    public function createConstraint(string $name): Constraint;

    /**
     * Change an existing constraint.
     *
     * @param string $name The constraint name
     *
     * @return Constraint
     */
    public function changeConstraint(string $name): Constraint;

    /**
     * Drop an existing constraint.
     *
     * @param string $name The constraint name
     *
     * @return Constraint
     */
    public function dropConstraint(string $name): Constraint;

    /**
     * Get the built query string.
     *
     * @return string
     */
    public function getQueryString(): string;
}
