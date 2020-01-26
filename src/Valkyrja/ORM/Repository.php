<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use InvalidArgumentException;
use PDO;
use Valkyrja\ORM\Exceptions\InvalidEntityException;

/**
 * Interface Repository.
 */
interface Repository
{
    /**
     * Get the store.
     *
     * @return PDO
     * @throws InvalidArgumentException
     */
    public function store(): PDO;

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     * @throws InvalidArgumentException If id is not a string or int
     */
    public function find($id, bool $getRelations = null): ?Entity;

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
     * </code>
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
    public function findBy(
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array;

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
     * </code>
     *
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(array $orderBy = null, array $columns = null, bool $getRelations = null): array;

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
     * </code>
     *
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria): int;

    /**
     * Create a new model.
     * <code>
     *      $this->create(Entity::class)
     * </code>
     *
     * @param Entity $entity
     *
     * @return bool
     * @throws InvalidEntityException
     */
    public function create(Entity $entity): bool;

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     * <code>
     *      $this->save(Entity::class)
     * </code>
     *
     * @param Entity $entity
     *
     * @return bool
     * @throws InvalidEntityException
     */
    public function save(Entity $entity): bool;

    /**
     * Delete an existing model.
     * <code>
     *      $this->delete(Entity::class)
     * </code>
     *
     * @param Entity $entity
     *
     * @return bool
     * @throws InvalidEntityException
     */
    public function delete(Entity $entity): bool;

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string;
}
