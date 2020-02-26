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
     * Find a single entity given its id.
     * <code>
     *      $repository
     *          ->find(
     *              1,
     *              true | false | null
     *          )
     * </code>.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function find($id, bool $getRelations = false): ?Entity;

    /**
     * Find one entity by given criteria.
     * <code>
     *      $repository
     *          ->findOneBy(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): ?Entity;

    /**
     * Find entities by given criteria.
     * <code>
     *      $repository
     *          ->findBy(
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ]
     *          )
     * </code>.
     *
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(array $orderBy = null, array $columns = null, bool $getRelations = false): array;

    /**
     * Find entities by given criteria.
     * <code>
     *      $repository
     *          ->findBy(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAllBy(
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): array;

    /**
     * Count all the results of given criteria.
     * <code>
     *      $repository
     *          ->count(
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ]
     *          )
     * </code>.
     *
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria): int;

    /**
     * Create a new model.
     * <code>
     *      $repository->create(Entity::class)
     * </code>.
     *
     * @param Entity $entity
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
