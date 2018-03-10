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
use PDOStatement;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;

/**
 * Class PDORepository.
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
     * @var string|\Valkyrja\ORM\Entity
     */
    protected $entity;

    /**
     * The table to use.
     *
     * @var string
     */
    protected $table;

    /**
     * The PDO Store.
     *
     * @var PDO
     */
    protected $store;

    /**
     * MySQLRepository constructor.
     *
     * @param EntityManager $entityManager
     * @param string        $entity
     *
     * @throws InvalidArgumentException
     */
    public function __construct(EntityManager $entityManager, string $entity)
    {
        $this->entityManager = $entityManager;
        $this->entity        = $entity;
        $this->table         = $this->entity::getTable();
        $this->store         = $this->entityManager->store();
    }

    /**
     * Get the store.
     *
     * @return PDO
     */
    public function store(): PDO
    {
        return $this->store;
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     *
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return \Valkyrja\ORM\Entity|null
     */
    public function find($id): ? Entity
    {
        if (! \is_string($id) && ! \is_int($id)) {
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
     * @throws InvalidArgumentException
     *
     * @return \Valkyrja\ORM\Entity[]
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
     * @throws InvalidArgumentException
     *
     * @return \Valkyrja\ORM\Entity[]
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
     * @throws InvalidArgumentException
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
     *      $this->create(Entity::class)
     * </code>
     *
     * @param \Valkyrja\ORM\Entity $entity
     *
     * @throws ExecuteException
     * @throws InvalidArgumentException
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function create(Entity $entity): bool
    {
        $this->validateEntity($entity);

        return $this->saveCreateDelete('insert', $entity);
    }

    /**
     * Save an existing model given criteria to find. If no criteria specified uses all model properties.
     *
     * <code>
     *      $this->save(Entity::class)
     * </code>
     *
     * @param \Valkyrja\ORM\Entity $entity
     *
     * @throws ExecuteException
     * @throws InvalidArgumentException
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $this->validateEntity($entity);

        return $this->saveCreateDelete('update', $entity);
    }

    /**
     * Delete an existing model.
     *
     * <code>
     *      $this->delete(Entity::class)
     * </code>
     *
     * @param \Valkyrja\ORM\Entity $entity
     *
     * @throws ExecuteException
     * @throws InvalidArgumentException
     * @throws InvalidEntityException
     *
     * @return bool
     */
    public function delete(Entity $entity): bool
    {
        $this->validateEntity($entity);

        return $this->saveCreateDelete('delete', $entity);
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->store->lastInsertId();
    }

    /**
     * Validate the passed entity.
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    protected function validateEntity(Entity $entity): void
    {
        if (! ($entity instanceof $this->entity)) {
            throw new InvalidEntityException(
                'This repository expects entities to be instances of '
                . $this->entity
                . '. Entity instanced from '
                . \get_class($entity)
                . ' provided instead.'
            );
        }
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
     * Get a criterion param from a criterion name.
     *
     * @param string $criterion
     *
     * @return string
     */
    protected function criterionParam(string $criterion): string
    {
        return ':criterion_' . $criterion;
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
     * @throws InvalidArgumentException
     *
     * @return \Valkyrja\ORM\Entity[]|int
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
        $stmt = $this->store->prepare($query->getQuery());

        // Iterate through the criteria once more
        foreach ($criteria as $column => $criterion) {
            // And bind each value to the column
            $stmt->bindValue($this->columnParam($column), $criterion);
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
            /** @var \Valkyrja\ORM\Entity $model */
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
     *              Entity::class
     *          )
     * </code>
     *
     * @param string $type
     * @param Entity $entity
     *
     * @throws ExecuteException
     * @throws InvalidArgumentException
     *
     * @return int
     */
    protected function saveCreateDelete(string $type, Entity $entity): int
    {
        if (! $this->store->inTransaction()) {
            $this->store->beginTransaction();
        }

        // Create a new query
        $query      = $this->entityManager
            ->getQueryBuilder()
            ->table($this->table)
            ->{$type}();
        $idField    = $entity::getIdField();
        $properties = $entity->asArray(false, false);

        /* @var QueryBuilder $query */

        // If this type isn't an insert
        if ($type !== 'insert') {
            // Set the id for the where clause
            $query->where($idField . ' = ' . $this->criterionParam($idField));
        }

        // Set the properties
        $this->setPropertiesForSaveCreateDeleteQuery($query, $properties);

        // Prepare a PDO statement with the query
        $stmt = $this->store->prepare($query->getQuery());

        // If this type isn't an insert
        if ($type !== 'insert') {
            // Set the id value for the where clause
            $stmt->bindValue($this->criterionParam($idField), $properties[$idField]);
        }

        // Set the properties.
        $this->setPropertiesForSaveCreateDeleteStatement($stmt, $properties);

        // If the execute failed
        if (! $executeResult = $stmt->execute()) {
            // Throw a fail exception
            throw new ExecuteException($stmt->errorInfo()[2]);
        }

        return $executeResult;
    }

    /**
     * Set properties for save, delete, or create queries.
     *
     * @param QueryBuilder $query
     * @param array        $properties
     *
     * @return void
     */
    protected function setPropertiesForSaveCreateDeleteQuery(QueryBuilder $query, array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $column => $property) {
            if ($property === null) {
                continue;
            }

            // Set the column and param name
            $query->set($column, $this->columnParam($column));
        }
    }

    /**
     * Set properties for save, create, or delete statements.
     *
     * @param PDOStatement $statement
     * @param array        $properties
     *
     * @return void
     */
    protected function setPropertiesForSaveCreateDeleteStatement(PDOStatement $statement, array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $column => $property) {
            if ($property === null) {
                continue;
            }

            // If the property is an object, then serialize it
            if (\is_object($property)) {
                $property = \serialize($property);
            } // Otherwise json encode if its an array
            elseif (\is_array($property)) {
                $property = \json_encode($property);
            }

            $type = PDO::PARAM_STR;

            if (\is_int($property) || \is_bool($property)) {
                $type = PDO::PARAM_INT;
                $property = (int) $property;
            }

            // Bind each column's value to the statement
            $statement->bindValue($this->columnParam($column), $property, $type);
        }
    }
}
