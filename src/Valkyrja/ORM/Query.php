<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

/**
 * Interface Query.
 *
 * @author Melech Mizrachi
 */
interface Query
{
    /**
     * Set the table to query on.
     *
     * @param string $table
     *
     * @return static
     */
    public function table(string $table): self;

    /**
     * Set the entity to query with.
     *
     * @param string $entity
     *
     * @return static
     */
    public function entity(string $entity): self;

    /**
     * Prepare the query.
     *
     * @param string $query
     *
     * @return static
     */
    public function prepare(string $query): self;

    /**
     * Bind a value.
     *
     * @param string $column
     * @param mixed  $property
     *
     * @return static
     */
    public function bindValue(string $column, $property): self;

    /**
     * Execute the query.
     *
     * @return bool
     */
    public function execute(): bool;

    /**
     * Get the result(s).
     *
     * @return mixed
     */
    public function getResult();

    /**
     * Get the error if one occurred.
     *
     * @return string
     */
    public function getError(): string;
}
