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

interface IndexContract
{
    /**
     * Create the index.
     */
    public function create(): static;

    /**
     * Rename the index.
     *
     * @param string $name The new name
     */
    public function rename(string $name): static;

    /**
     * Drop the index.
     */
    public function drop(): static;

    /**
     * Set the name of the index.
     *
     * @param string $name The index name
     */
    public function setName(string $name): static;

    /**
     * Add a column to the index.
     *
     * @param string $name The index name
     */
    public function addColumn(string $name): static;

    /**
     * Set this as a unique index.
     */
    public function isUnique(): static;

    /**
     * Do only if the table doesn't already exist.
     */
    public function ifNotExists(): static;

    /**
     * Do only if the table exists.
     */
    public function ifExists(): static;
}
