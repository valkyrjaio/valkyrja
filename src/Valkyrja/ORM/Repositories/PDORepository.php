<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Repositories;

use InvalidArgumentException;
use PDO;
use Valkyrja\Model\Model;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;

/**
 * Class MySQLRepository.
 */
class PDORepository implements Repository
{
    /**
     * The entity manager.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * The entity to use.
     *
     * @var string|Model
     */
    protected $entity;

    /**
     * The table to use.
     *
     * @var string
     */
    protected $table;

    /**
     * MySQLRepository constructor.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     * @param string        $table
     */
    public function __construct(EntityManager $entityManager, string $entity, string $table)
    {
        $this->entityManager = $entityManager;
        $this->entity        = $entity;
        $this->table         = $table;
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     *
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return Model|null
     */
    public function find($id): ? Model
    {
        if (! \is_string($id) || ! \is_int($id)) {
            throw new InvalidArgumentException('ID should be an int or string only.');
        }

        return $this->findBy(['id', $id])[0] ?? null;
    }

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
     * @return Model[]
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->select(null, $criteria, $orderBy, $limit, $offset);
    }

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
     * @return Model[]
     */
    public function findAll(array $orderBy = null): array
    {
        return $this->findBy([], $orderBy);
    }

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
    public function count(array $criteria): int
    {
        return $this->select(['COUNT(*)'], $criteria);
    }

    /**
     * Create a new model.
     *
     * <code>
     *      $this->create(Model::class)
     * </code>
     *
     * @param Model $model
     *
     * @return bool
     */
    public function create(Model $model): bool
    {
        return $this->saveCreateDelete('insert', $model->jsonSerialize());
    }

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     *
     * <code>
     *      $this
     *          ->save(
     *              Model::class,
     *              [
     *                  'column' => 'value',
     *              ]
     *          )
     * </code>
     *
     * @param Model      $model
     * @param array|null $criteria
     *
     * @return bool
     */
    public function save(Model $model, array $criteria = null): bool
    {
        return $this->saveCreateDelete('update', $criteria ?? $model->jsonSerialize());
    }

    /**
     * Delete an existing model.
     *
     * <code>
     *      $this
     *          ->delete(
     *              Model::class,
     *              [
     *                  'column' => 'value',
     *              ]
     *          )
     * </code>
     *
     * @param Model      $model
     * @param array|null $criteria
     *
     * @return bool
     */
    public function delete(Model $model, array $criteria = null): bool
    {
        return $this->saveCreateDelete('delete', $criteria ?? $model->jsonSerialize());
    }

    /**
     * Get a column param from a column name.
     *
     * @param string $column
     *
     * @return string
     */
    protected function columnParam(string $column): string
    {
        return ':' . $column;
    }

    /**
     * Select entities by given criteria.
     *
     * <code>
     *      $this->select(
     *              [
     *                  'column',
     *                  'column2',
     *              ],
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
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return Model[]|int
     */
    protected function select(
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ) {
        // Build the query
        $query = $this->selectQueryBuilder($columns, $criteria, $orderBy, $limit, $offset);

        // Create a new PDO statement from the query builder
        $stmt = $this->entityManager->getPDO()->prepare($query->getQuery());

        // Iterate through the criteria once more
        foreach ($criteria as $column => $criterion) {
            // And bind each value to the column
            $stmt->bindParam($this->columnParam($column), $criterion);
        }

        // Execute the PDO statement
        $stmt->execute();

        // Get all the results from the PDO statement
        $rows    = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = [];

        // If the result of the query was a count
        if (isset($rows[0]) && \is_int($rows[0])) {
            return $rows[0];
        }

        // Iterate through the rows found
        foreach ($rows as $row) {
            // Create a new model
            /** @var Model $model */
            $model = new $this->entity();
            // Apply the model's contents given the row
            $model->fromArray($row);

            // Add the model to the final results
            $results[] = $model;
        }

        return $results;
    }

    /**
     * Build a select query statement by given criteria.
     *
     * <code>
     *      $this->queryBuilder(
     *              [
     *                  'column',
     *                  'column2',
     *              ],
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
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return QueryBuilder
     */
    protected function selectQueryBuilder(
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): QueryBuilder {
        // Create a new query
        $query = $this->entityManager
            ->getQueryBuilder()
            ->select($columns)
            ->table($this->table);

        // If criteria has been passed
        if (null !== $criteria) {
            // Iterate through each criteria and set the column = :column
            // so we can use bindColumn() in PDO later
            foreach ($criteria as $column => $criterion) {
                // Set the where condition
                $query->where($column . ' = ' . $this->columnParam($column));
            }
        }

        // If order by has been passed
        if (null !== $orderBy) {
            // Iterate through each order by
            foreach ($orderBy as $column => $order) {
                // Switch through the order (value) set
                switch ($order) {
                    // If the order is ascending
                    case OrderBy::ASC:
                        // Set the column via the orderByAsc method
                        $query->orderByAsc($column);
                        break;
                    // If the order is descending
                    case OrderBy::DESC:
                        // Set the column via the orderByDesc method
                        $query->orderByDesc($column);
                        break;
                    default:
                        // Otherwise set the order (which is the column)
                        $query->orderBy($order);
                        break;
                }
            }
        }

        // If a limit is passed
        if (null !== $limit) {
            // Set it in the query
            $query->limit($limit);
        }

        // If an offset is passed
        if (null !== $offset) {
            // Set it in the query
            $query->offset($offset);
        }

        return $query;
    }

    /**
     * Save or create or delete a row.
     *
     * <code>
     *      $this
     *          ->saveOrCreate(
     *             'update' | 'insert' | 'delete',
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value',
     *              ]
     *          )
     * </code>
     *
     * @param string $type
     * @param array  $properties
     *
     * @return int
     */
    protected function saveCreateDelete(string $type, array $properties): int
    {
        // Create a new query
        $query = $this->entityManager
            ->getQueryBuilder()
            ->table($this->table)
            ->{$type}();

        /* @var QueryBuilder $query */

        // Iterate through the properties
        foreach ($properties as $column => $property) {
            // Set the column and param name
            $query->set($column, $this->columnParam($column));
        }

        // Prepare a PDO statement with the query
        $stmt = $this->entityManager->getPDO()->prepare($query->getQuery());

        // Iterate through the properties
        foreach ($properties as $column => $property) {
            // If the property is an object, then serialize it
            if (\is_object($property)) {
                $property = \serialize($property);
            } // Otherwise json encode if its an array
            elseif (\is_array($property)) {
                $property = \json_encode($property);
            }

            // Bind each column's value to the statement
            $stmt->bindParam($this->columnParam($column), $property);
        }

        return $stmt->execute();
    }
}
