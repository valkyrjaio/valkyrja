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

namespace Valkyrja\Orm\Repository\Contract;

use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;

/**
 * @template T of EntityContract
 */
interface RepositoryContract
{
    /**
     * Find an entity by its id.
     *
     * @param non-empty-string|int $id The id of the entity to find
     *
     * @return T|null
     */
    public function find(string|int $id): EntityContract|null;

    /**
     * Find an entity by some conditions.
     *
     * @param Where ...$where The where clauses
     *
     * @return T|null
     */
    public function findBy(Where ...$where): EntityContract|null;

    /**
     * Get all entities.
     *
     * @return T[]
     */
    public function all(): array;

    /**
     * Get many with some conditions.
     *
     * @param Where ...$where The where clauses
     *
     * @return T[]
     */
    public function allBy(Where ...$where): array;

    /**
     * Create a new entity.
     *
     * The repository stamps the created date and the modified date on a
     * DatedEntityContract entity.
     *
     * @param T $entity The entity
     */
    public function create(EntityContract $entity): void;

    /**
     * Update an entity.
     *
     * The repository stamps the modified date on a DatedEntityContract entity.
     * The repository never writes the deleted date here, so an update does not
     * delete an entity, and an update does not restore a deleted entity.
     *
     * @param T $entity The entity
     */
    public function update(EntityContract $entity): void;

    /**
     * Delete an entity.
     *
     * The repository soft deletes a SoftDeleteEntityContract entity: it stamps
     * the deleted date and it keeps the row. For every other entity the
     * repository removes the row.
     *
     * @param T $entity The entity
     */
    public function delete(EntityContract $entity): void;

    /**
     * Remove the row of an entity.
     *
     * Warning: the method removes the row of a SoftDeleteEntityContract entity,
     * and the soft delete does not protect the data. Use the method only when a
     * law or a data policy requires the removal, such as an erasure request.
     *
     * @param T $entity The entity
     */
    public function forceDelete(EntityContract $entity): void;
}
