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

use stdClass;
use Valkyrja\ORM\Exceptions\NotFoundException;

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
     * @param class-string<Entity> $entity
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
     * @param string $property
     * @param mixed  $value
     *
     * @return static
     */
    public function bindValue(string $property, mixed $value): self;

    /**
     * Execute the query.
     *
     * @return bool
     */
    public function execute(): bool;

    /**
     * Get the result.
     *
     * @return Entity[]|stdClass[]
     */
    public function getResult(): array;

    /**
     * Get one or null.
     *
     * @return Entity|stdClass|null
     */
    public function getOneOrNull(): Entity|stdClass|null;

    /**
     * Get one or fail.
     *
     * @throws NotFoundException
     *
     * @return Entity|object
     */
    public function getOneOrFail(): object;

    /**
     * Get count results.
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Get the error if one occurred.
     *
     * @return string
     */
    public function getError(): string;
}
