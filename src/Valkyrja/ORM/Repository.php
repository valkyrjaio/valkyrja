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
 * Interface Repository.
 *
 * @author Melech Mizrachi
 */
interface Repository
{
    /**
     * Make a new repository.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     *
     * @return static
     */
    public static function make(EntityManager $entityManager, string $entity): self;

    /**
     * Find by given criteria.
     *
     * @param bool|null $getRelations
     *
     * @return Retriever
     */
    public function find(bool $getRelations = false): Retriever;

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Retriever
     */
    public function findOne($id, bool $getRelations = false): Retriever;

    /**
     * Count all the results of given criteria.
     *
     * @return Retriever
     */
    public function count(): Retriever;

    /**
     * Create a new model.
     * <code>
     *      $repository->create(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void;

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     * <code>
     *      $repository->save(Entity::class)
     * </code>.
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void;

    /**
     * Delete an existing model.
     * <code>
     *      $repository->delete(Entity::class)
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
     *      $repository
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
     * Get a new query builder instance.
     *
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder;

    /**
     * Create a new query.
     *
     * @param string $query
     *
     * @return Query
     */
    public function createQuery(string $query): Query;
}
