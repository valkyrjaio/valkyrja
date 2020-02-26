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
 * Interface Persister
 *
 * @author Melech Mizrachi
 */
interface Persister
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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void;

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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void;

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
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void;

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
