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

interface ConstraintContract
{
    /**
     * Create the constraint.
     */
    public function create(): static;

    /**
     * Rename the constraint.
     *
     * @param string $name The new name
     */
    public function rename(string $name): static;

    /**
     * Drop the constraint.
     */
    public function drop(): static;

    /**
     * Set the name of the constraint.
     *
     * @param string $name The constraint name
     */
    public function setName(string $name): static;

    /**
     * Add a column to the constraint.
     *
     * @param string $name The column name
     */
    public function addColumn(string $name): static;

    /**
     * Set this as a primary key constraint.
     */
    public function isPrimaryKey(): static;

    /**
     * Do only if the table doesn't already exist.
     */
    public function ifNotExists(): static;

    /**
     * Do only if the table exists.
     */
    public function ifExists(): static;
}
