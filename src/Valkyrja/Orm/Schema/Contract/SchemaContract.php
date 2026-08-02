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

interface SchemaContract
{
    /**
     * Create a new table.
     *
     * @param string $name The table name
     */
    public function createTable(string $name): TableContract;

    /**
     * Get an existing table.
     *
     * @param string $name The table name
     */
    public function getTable(string $name): TableContract;

    /**
     * Rename an existing table.
     *
     * @param string $name    The table name
     * @param string $newName The table's new name
     */
    public function renameTable(string $name, string $newName): TableContract;

    /**
     * Drop a table.
     *
     * @param string $name The table name
     */
    public function dropTable(string $name): TableContract;

    /**
     * Get the built query string.
     */
    public function getQueryString(): string;

    /**
     * Execute a table query.
     *
     * @param TableContract $table The table to execute
     */
    public function execute(TableContract $table): bool;

    /**
     * Execute all pending table queries.
     */
    public function executeAll(): bool;

    /**
     * Get the error if one occurred.
     */
    public function getError(): string;
}
