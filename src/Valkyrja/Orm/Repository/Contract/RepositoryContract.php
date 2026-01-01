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
     * @param T $entity The entity
     *
     * @return void
     */
    public function create(EntityContract $entity): void;

    /**
     * Update an entity.
     *
     * @param T $entity The entity
     *
     * @return void
     */
    public function update(EntityContract $entity): void;

    /**
     * Delete an entity.
     *
     * @param T $entity The entity
     *
     * @return void
     */
    public function delete(EntityContract $entity): void;
}
