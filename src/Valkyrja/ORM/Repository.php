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
     * @throws InvalidArgumentException
     *
     * @return PDO
     */
    public function store(): PDO;

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     *
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return \Valkyrja\ORM\Entity|null
     */
    public function find($id): ? Entity;

    /**
     * Find entities by given criteria.
     *
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
     *
     * @return \Valkyrja\ORM\Entity[]
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array;

    /**
     * Find entities by given criteria.
     *
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
     * @param array $orderBy
     *
     * @return \Valkyrja\ORM\Entity[]
     */
    public function findAll(array $orderBy = null): array;

    /**
     * Count all the results of given criteria.
     *
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
     *
     * <code>
     *      $this->create(Entity::class)
     * </code>
     *
     * @param \Valkyrja\ORM\Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function create(Entity $entity): bool;

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     *
     * <code>
     *      $this->save(Entity::class)
     * </code>
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function save(Entity $entity): bool;

    /**
     * Delete an existing model.
     *
     * <code>
     *      $this->delete(Entity::class)
     * </code>
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function delete(Entity $entity): bool;

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string;
}
