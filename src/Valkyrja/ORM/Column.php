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

namespace Valkyrja\ORM;

/**
 * Interface Column.
 *
 * @author Melech Mizrachi
 */
interface Column
{
    /**
     * Create the column.
     *
     * @return static
     */
    public function create(): self;

    /**
     * Rename the column.
     *
     * @param string $name The new name
     *
     * @return static
     */
    public function rename(string $name): self;

    /**
     * Drop the column.
     *
     * @return static
     */
    public function drop(): self;

    /**
     * Set the name of the column.
     *
     * @param string $name The column name
     *
     * @return static
     */
    public function setName(string $name): self;

    /**
     * Set the column's type.
     *
     * @param string   $type   The type
     * @param int|null $length [optional] The length constraint for this column
     *
     * @return static
     */
    public function setType(string $type, int $length = null): self;

    /**
     * Set the default value for this column.
     *
     * @param string|int|bool $value [optional] The default value
     *
     * @return static
     */
    public function setDefault($value = null): self;

    /**
     * Set this as a non-nullable column.
     *
     * @return static
     */
    public function isNotNullable(): self;

    /**
     * Set this as a primary key column.
     *
     * @return static
     */
    public function isPrimaryKey(): self;

    /**
     * Set this as a unique column.
     *
     * @return static
     */
    public function isUnique(): self;

    /**
     * Set this as an auto incrementing column.
     *
     * @return static
     */
    public function isAutoIncrementing(): self;

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
}
