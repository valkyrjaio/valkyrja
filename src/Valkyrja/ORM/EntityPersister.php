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
 * Interface EntityPersister
 *
 * @author Melech Mizrachi
 */
interface EntityPersister
{
    /**
     * Set a model for creation on transaction commit.
     * <code>
     *      $entityPersister
     *          ->create(
     *              new Entity()
     *         )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function create(Entity $entity): void;

    /**
     * Set a model for saving on transaction commit.
     * <code>
     *      $entityPersister
     *          ->save(
     *              new Entity()
     *          )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function save(Entity $entity): void;

    /**
     * Set a model for deletion on transaction commit.
     * <code>
     *      $entityPersister
     *          ->delete(
     *              new Entity()
     *          )
     * </code>.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function delete(Entity $entity): void;

    /**
     * Clear a model previously set for creation, save, or deletion.
     * <code>
     *      $entityPersister
     *          ->clear(
     *              new Entity()
     *          )
     * </code>.
     *
     * @param Entity|null $entity The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void;

    /**
     * Persist all entities.
     *
     * @return void
     */
    public function persist(): void;
}
