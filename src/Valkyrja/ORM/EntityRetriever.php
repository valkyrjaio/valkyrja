<?php

declare(strict_types = 1);

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
 * Interface EntityRetriever
 *
 * @author Melech Mizrachi
 */
interface EntityRetriever
{
    /**
     * Find a single entity given its id.
     * <code>
     *      $entityRetriever
     *          ->find(
     *              Entity::class,
     *              1,
     *              true | false | null
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function find(string $entity, $id, bool $getRelations = null): ?Entity;

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findBy(
     *              Entity::class,
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
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array;

    /**
     * Find one entity by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findOneBy(
     *              Entity::class,
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
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity
     */
    public function findOneBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): Entity;

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findBy(
     *              Entity::class,
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ]
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = null
    ): array;

    /**
     * Count all the results of given criteria.
     * <code>
     *      $entityRetriever
     *          ->count(
     *              Entity::class,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ]
     *          )
     * </code>.
     *
     * @param string $entity
     * @param array  $criteria
     *
     * @return int
     */
    public function count(string $entity, array $criteria): int;
}
