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
 * Interface SchemaContract.
 */
interface SchemaContract
{
    /**
     * Create a new table.
     *
     * @param string $name The table name
     *
     * @return TableContract
     */
    public function createTable(string $name): TableContract;

    /**
     * Get an existing table.
     *
     * @param string $name The table name
     *
     * @return TableContract
     */
    public function getTable(string $name): TableContract;

    /**
     * Rename an existing table.
     *
     * @param string $name    The table name
     * @param string $newName The table's new name
     *
     * @return TableContract
     */
    public function renameTable(string $name, string $newName): TableContract;

    /**
     * Drop a table.
     *
     * @param string $name The table name
     *
     * @return TableContract
     */
    public function dropTable(string $name): TableContract;

    /**
     * Get the built query string.
     *
     * @return string
     */
    public function getQueryString(): string;

    /**
     * Execute a table query.
     *
     * @param TableContract $table The table to execute
     *
     * @return bool
     */
    public function execute(TableContract $table): bool;

    /**
     * Execute all pending table queries.
     *
     * @return bool
     */
    public function executeAll(): bool;

    /**
     * Get the error if one occurred.
     *
     * @return string
     */
    public function getError(): string;
}
