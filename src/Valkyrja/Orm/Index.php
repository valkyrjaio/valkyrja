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
 * Interface Index.
 *
 * @author Melech Mizrachi
 */
interface Index
{
    /**
     * Create the index.
     *
     * @return static
     */
    public function create(): self;

    /**
     * Rename the index.
     *
     * @param string $name The new name
     *
     * @return static
     */
    public function rename(string $name): self;

    /**
     * Drop the index.
     *
     * @return static
     */
    public function drop(): self;

    /**
     * Set the name of the index.
     *
     * @param string $name The index name
     *
     * @return static
     */
    public function setName(string $name): self;

    /**
     * Add a column to the index.
     *
     * @param string $name The index name
     *
     * @return static
     */
    public function addColumn(string $name): self;

    /**
     * Set this as a unique index.
     *
     * @return static
     */
    public function isUnique(): self;

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
