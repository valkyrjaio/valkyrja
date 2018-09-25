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
use Valkyrja\ORM\Enums\PropertyType;
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
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return \Valkyrja\ORM\Entity|null
     */
    public function find($id, bool $getRelations = null): ? Entity
    {
        if (! \is_string($id) && ! \is_int($id)) {
            throw new InvalidArgumentException('ID should be an int or string only.');
        }

        return $this->findBy(
                [$this->entity::getIdField() => $id],
                null,
                null,
                null,
                null,
                $getRelations
            )[0]
            ?? null;
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
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
     *
     * @return \Valkyrja\ORM\Entity[]
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        return $this->select($columns, $criteria, $orderBy, $limit, $offset, $getRelations);
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
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
     *
     * @return \Valkyrja\ORM\Entity[]
     */
    public function findAll(array $orderBy = null, array $columns = null, bool $getRelations = null): array
    {
        return $this->findBy([], $orderBy, null, null, $columns, $getRelations);
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
     * @param bool|null  $getRelations
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
        int $offset = null,
        bool $getRelations = null
    ) {
        // Build the query
        $query = $this->selectQueryBuilder($columns, $criteria, $orderBy, $limit, $offset);

        // Create a new PDO statement from the query builder
        $stmt = $this->store->prepare($query->getQuery());

        // Iterate through the criteria once more
        foreach ($criteria as $column => $criterion) {
            // If the criterion is null
            if ($criterion === null) {
                // Skip as we've already set the where to IS NULL
                continue;
            }

            // If the criterion is an array
            if (\is_array($criterion)) {
                // Iterate through the criterion and bind each value individually
                foreach ($criterion as $index => $criterionItem) {
                    $stmt->bindValue($this->columnParam($column . $index), $criterionItem);
                }

                continue;
            }

            // And bind each value to the column
            $stmt->bindValue($this->columnParam($column), $criterion);
        }

        // Execute the PDO statement
        $stmt->execute();

        // Get all the results from the PDO statement
        $rows    = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = [];

        // If the result of the query was a count
        if (isset($rows[0]['COUNT(*)'])) {
            return (int) $rows[0]['COUNT(*)'];
        }

        // Iterate through the rows found
        foreach ($rows as $row) {
            // Create a new model
            /** @var \Valkyrja\ORM\Entity $entity */
            $entity = new $this->entity();
            // Apply the model's contents given the row
            $entity->fromArray($row);

            // If no columns were specified then we can safely get all the relations
            if (null === $columns && $getRelations === true) {
                // Add the model to the final results
                $results[] = $this->getEntityRelations($entity);
            } else {
                // Add the model to the final results
                $results[] = $entity;
            }
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
            $this->setCriteriaInQuery($query, $criteria);
        }

        // If order by has been passed
        if (null !== $orderBy) {
            $this->setOrderByInQuery($query, $orderBy);
        }

        // If a limit is passed
        if (null !== $limit) {
            $this->setLimitInQuery($query, $limit);
        }

        // If an offset is passed
        if (null !== $offset) {
            $this->setOffsetInQuery($query, $offset);
        }

        return $query;
    }

    /**
     * Set the criteria in the query builder.
     *
     * @param QueryBuilder $query
     * @param array        $criteria
     *
     * @return void
     */
    protected function setCriteriaInQuery(QueryBuilder $query, array $criteria): void
    {
        // Iterate through each criteria and set the column = :column
        // so we can use bindColumn() in PDO later
        foreach ($criteria as $column => $criterion) {
            // If the criterion is null
            if ($criterion === null) {
                $this->setNullCriterionInQuery($query, $column);

                continue;
            }

            // If the criterion is an array
            if (\is_array($criterion)) {
                $this->setArrayCriterionInQuery($query, $column, $criterion);

                continue;
            }

            // If the criterion has a percent at the start or the end
            if ($criterion[0] === '%' || $criterion[\strlen($criterion) - 1] === '%') {
                $this->setLikeCriterionInQuery($query, $column);

                continue;
            }

            $this->setEqualCriterionInQuery($query, $column);
        }
    }

    /**
     * Set a null criterion in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setNullCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' IS NULL');
    }

    /**
     * Set an array criterion in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     * @param array        $criterion
     *
     * @return void
     */
    protected function setArrayCriterionInQuery(QueryBuilder $query, string $column, array $criterion): void
    {
        $criterionConcat = '';
        $lastIndex       = \count($criterion) - 1;

        // Iterate through the criterion and set each item individually to be bound later
        foreach ($criterion as $index => $criterionItem) {
            $criterionConcat .= $this->columnParam($column . $index);

            // If this is not the last index, add a comma
            if ($index < $lastIndex) {
                $criterionConcat .= ',';
            }
        }

        // Set the where statement as an in
        $query->where($column . ' IN (' . $criterionConcat . ')');
    }

    /**
     * Set a like where statement for a criterion/column in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setLikeCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' LIKE ' . $this->columnParam($column));
    }

    /**
     * Set an equal where statement for a criterion/column in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setEqualCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' = ' . $this->columnParam($column));
    }

    /**
     * Set the order by options in a query builder.
     *
     * @param QueryBuilder $query
     * @param array        $orderBy
     *
     * @return void
     */
    protected function setOrderByInQuery(QueryBuilder $query, array $orderBy): void
    {
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

    /**
     * Set the limit in the query builder.
     *
     * @param QueryBuilder $query
     * @param int          $limit
     *
     * @return void
     */
    protected function setLimitInQuery(QueryBuilder $query, int $limit): void
    {
        // Set it in the query
        $query->limit($limit);
    }

    /**
     * Set the offset in the query builder.
     *
     * @param QueryBuilder $query
     * @param int          $offset
     *
     * @return void
     */
    protected function setOffsetInQuery(QueryBuilder $query, int $offset): void
    {
        // Set it in the query
        $query->offset($offset);
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
        $properties = $entity->forDataStore();

        /* @var QueryBuilder $query */

        // If this is an insert
        if ($type === 'insert') {
            // Ensure all the required properties are set
            $this->ensureRequiredProperties($entity, $properties);
        }

        // If this type isn't an insert
        if ($type !== 'insert') {
            // Set the id for the where clause
            $query->where($idField . ' = ' . $this->criterionParam($idField));
        }

        if ($type !== 'delete') {
            // Set the properties
            $this->setPropertiesForSaveCreateDeleteQuery($query, $properties);
        }

        // Prepare a PDO statement with the query
        $stmt = $this->store->prepare($query->getQuery());

        // If this type isn't an insert
        if ($type !== 'insert') {
            // Set the id value for the where clause
            $stmt->bindValue($this->criterionParam($idField), $properties[$idField]);
        }

        if ($type !== 'delete') {
            // Set the properties.
            $this->setPropertiesForSaveCreateDeleteStatement($stmt, $properties);
        }

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
                $type     = PDO::PARAM_INT;
                $property = (int) $property;
            }

            // Bind each column's value to the statement
            $statement->bindValue($this->columnParam($column), $property, $type);
        }
    }

    /**
     * Get an entity with all its relations.
     *
     * @param Entity $entity
     *
     * @return Entity
     */
    protected function getEntityRelations(Entity $entity): Entity
    {
        $propertyTypes  = $entity::getPropertyTypes();
        $propertyMapper = $entity->getPropertyMapper();

        // Iterate through the property types
        foreach ($propertyTypes as $property => $type) {
            $entityName  = \is_array($type) ? $type[0] : $type;
            $propertyMap = $propertyMapper[$property] ?? null;

            if (null !== $propertyMap && (\is_array($type) || ! PropertyType::isValid($type))) {
                $repository   = $this->entityManager->getRepository($entityName);
                $orderBy      = $propertyMap['orderBy'] ?? null;
                $limit        = $propertyMap['limit'] ?? null;
                $offset       = $propertyMap['offset'] ?? null;
                $columns      = $propertyMap['columns'] ?? null;
                $getRelations = $propertyMap['getRelations'] ?? true;

                unset(
                    $propertyMap['orderBy'],
                    $propertyMap['limit'],
                    $propertyMap['offset'],
                    $propertyMap['columns'],
                    $propertyMap['getRelations']
                );

                $entities = $repository->findBy($propertyMap, $orderBy, $limit, $offset, $columns, $getRelations);

                if (\is_array($type)) {
                    $entity->{$property} = $entities;

                    continue;
                }

                if (empty($entities)) {
                    continue;
                }

                $entity->{$property} = $entities[0];
            }
        }

        return $entity;
    }

    /**
     * Ensure all required properties have been passed.
     *
     * @param Entity $entity
     * @param array  $properties
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function ensureRequiredProperties(Entity $entity, array $properties): void
    {
        // Iterate through the required properties
        foreach ($entity::getRequiredProperties() as $requiredProperty) {
            // If the required property is not set
            if (! isset($properties[$requiredProperty])) {
                // Throw an exception
                throw new InvalidArgumentException('Missing required property: ' . $requiredProperty);
            }
        }
    }
}
