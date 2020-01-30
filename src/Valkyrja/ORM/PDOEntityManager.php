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
use PDOStatement;
use Valkyrja\Application;
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Enums\PropertyMap;
use Valkyrja\ORM\Enums\PropertyType;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\QueryBuilder\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\NativeRepository;
use Valkyrja\Support\Providers\Provides;

/**
 * Class PDOEntityManager.
 *
 * @author Melech Mizrachi
 */
class PDOEntityManager implements EntityManager
{
    use Provides;

    protected const INSERT = 'insert';
    protected const UPDATE = 'update';
    protected const DELETE = 'delete';

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected array $repositories = [];

    /**
     * Stores.
     *
     * @var PDO[]
     */
    protected array $stores = [];

    /**
     * The entities awaiting to be committed for creation.
     * <code>
     *      [
     *          Entity::class
     *      ]
     * </code>.
     *
     * @var Entity[]
     */
    protected array $createEntities = [];

    /**
     * The entities awaiting to be committed for saving.
     * <code>
     *      [
     *          Entity::class
     *      ]
     * </code>.
     *
     * @var Entity[]
     */
    protected array $saveEntities = [];

    /**
     * The entities awaiting to be committed for deletion.
     * <code>
     *      [
     *          Entity::class
     *      ]
     * </code>.
     *
     * @var Entity[]
     */
    protected array $deleteEntities = [];

    /**
     * PDOEntityManager' constructor.
     *
     * @param Application $app
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->store()->beginTransaction();
    }

    /**
     * Get a new query builder instance.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return new SqlQueryBuilder();
    }

    /**
     * Get a repository instance.
     *
     * @param string $entity
     *
     * @return Repository
     */
    public function getRepository(string $entity): Repository
    {
        if (isset($this->repositories[$entity])) {
            return $this->repositories[$entity];
        }

        /** @var Entity|string $entity */
        $repository = $entity::getRepository() ?? NativeRepository::class;

        return $this->repositories[$entity] = new $repository($this, $entity, $entity::getTable());
    }

    /**
     * Initiate a transaction.
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->store()->beginTransaction();
    }

    /**
     * Commit all items in the transaction.
     *
     * @throws InvalidArgumentException
     * @throws ExecuteException
     *
     * @return bool
     */
    public function commit(): bool
    {
        // Iterate through the models awaiting creation
        foreach ($this->createEntities as $cid => $createEntity) {
            // Create the model
            $this->saveCreateDelete(self::UPDATE, $createEntity);
            // Unset the model
            unset($this->createEntities[$cid]);
        }

        // Iterate through the models awaiting save
        foreach ($this->saveEntities as $sid => $saveEntity) {
            // Save the model
            $this->saveCreateDelete(self::INSERT, $saveEntity);
            // Unset the model
            unset($this->saveEntities[$sid]);
        }

        // Iterate through the models awaiting deletion
        foreach ($this->deleteEntities as $sid => $deleteEntity) {
            // Save the model
            $this->saveCreateDelete(self::DELETE, $deleteEntity);
            // Unset the model
            unset($this->deleteEntities[$sid]);
        }

        return $this->store()->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->store()->rollBack();
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->store()->lastInsertId();
    }

    /**
     * Find a single entity given its id.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return Entity|null
     */
    public function find(string $entity, $id, bool $getRelations = null): ?Entity
    {
        if (! is_string($id) && ! is_int($id)) {
            throw new InvalidArgumentException('ID should be an int or string only.');
        }

        /** @var Entity|string $entity */

        return $this->findBy(
                $entity,
                [$entity::getIdField() => $id],
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
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
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
    ): array {
        return $this->select($entity, $columns, $criteria, $orderBy, $limit, $offset, $getRelations);
    }

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
     * @param string     $entity
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        return $this->findBy($entity, [], $orderBy, null, null, $columns, $getRelations);
    }

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
     * @param string $entity
     * @param array  $criteria
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function count($entity, array $criteria): int
    {
        return (int) $this->select($entity, ['COUNT(*)'], $criteria);
    }

    /**
     * Set a model for creation on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function create(Entity $entity): void
    {
        $id = spl_object_id($entity);

        $this->createEntities[$id] = $entity;
    }

    /**
     * Set a model for saving on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function save(Entity $entity): void
    {
        $id = spl_object_id($entity);

        $this->saveEntities[$id] = $entity;
    }

    /**
     * Set a model for deletion on transaction commit.
     *
     * @param Entity $entity
     *
     * @return void
     */
    public function delete(Entity $entity): void
    {
        $id = spl_object_id($entity);

        $this->deleteEntities[$id] = $entity;
    }

    /**
     * Remove a model previously set for creation, save, or deletion.
     *
     * @param Entity $entity The entity instance to remove.
     *
     * @return bool
     */
    public function remove(Entity $entity): bool
    {
        // Get the id of the object
        $id = spl_object_id($entity);

        // If the model is set to be created
        if (isset($this->createEntities[$id])) {
            // Unset it
            unset($this->createEntities[$id]);

            return true;
        }

        // If the model is set to be saved
        if (isset($this->saveEntities[$id])) {
            // Unset it
            unset($this->saveEntities[$id]);

            return true;
        }

        // If the model is set to be deleted
        if (isset($this->deleteEntities[$id])) {
            // Unset it
            unset($this->deleteEntities[$id]);

            return true;
        }

        // The model wasn't set for creation or saving
        return false;
    }

    /**
     * Get a pdo store by name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return PDO
     */
    protected function store(string $name = null): PDO
    {
        $name = $name ?? $this->app->config()['database']['default'];

        if (isset($this->stores[$name])) {
            return $this->stores[$name];
        }

        $config = $this->getStoreConfig($name);

        return $this->stores[$name] = $this->getStoreFromConfig($config);
    }

    /**
     * Get the store config.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getStoreConfig(string $name): array
    {
        $config = $this->app->config('database.connections.' . $name);

        if (null === $config) {
            throw new InvalidArgumentException('Invalid connection name specified: ' . $name);
        }

        return $config;
    }

    /**
     * Get the store from the config.
     *
     * @param array $config
     *
     * @return PDO
     */
    protected function getStoreFromConfig(array $config): PDO
    {
        $dsn = $config['driver']
            . ':host=' . $config['host']
            . ';port=' . $config['port']
            . ';dbname=' . $config['database']
            . ';charset=' . $config['charset'];

        return new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            []
        );
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
     * </code>.
     *
     * @param string     $entity
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
     *
     * @return Entity[]|int
     */
    protected function select(
        string $entity,
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        bool $getRelations = null
    ) {
        // Build the query
        $query = $this->selectQueryBuilder($entity, $columns, $criteria, $orderBy, $limit, $offset);

        // Create a new PDO statement from the query builder
        $stmt = $this->store()->prepare($query->getQuery());

        // Bind criteria
        $this->bindValuesForSelect($stmt, $criteria);

        // Execute the PDO statement
        $stmt->execute();

        // Get all the results from the PDO statement
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If the result of the query was a count
        if (isset($rows[0]['COUNT(*)'])) {
            return (int) $rows[0]['COUNT(*)'];
        }

        return $this->getSelectResultsAsEntities($entity, $rows, $columns, $getRelations);
    }

    /**
     * Bind criteria for a select statement.
     *
     * @param PDOStatement $statement
     * @param array|null   $criteria
     *
     * @return void
     */
    protected function bindValuesForSelect(PDOStatement $statement, array $criteria = null): void
    {
        // Iterate through the criteria once more
        foreach ($criteria as $column => $criterion) {
            // If the criterion is null
            if ($criterion === null) {
                // Skip as we've already set the where to IS NULL
                continue;
            }

            // If the criterion is an array
            if (is_array($criterion)) {
                // Iterate through the criterion and bind each value individually
                foreach ($criterion as $index => $criterionItem) {
                    $this->bindValue($statement, $column . $index, $criterionItem);
                }

                continue;
            }

            // And bind each value to the column
            $this->bindValue($statement, $column, $criterion);
        }
    }

    /**
     * Bind a value to a statement.
     *
     * @param PDOStatement $statement
     * @param string       $column
     * @param mixed        $property
     *
     * @return void
     */
    protected function bindValue(PDOStatement $statement, string $column, $property): void
    {
        // And bind each value to the column
        $statement->bindValue(
            $this->columnParam($column),
            $property,
            $this->getBindValueType($property)
        );
    }

    /**
     * Get value type to bind with.
     *
     * @param mixed $property
     *
     * @return int
     */
    protected function getBindValueType($property): int
    {
        $type = PDO::PARAM_STR;

        if (is_int($property)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($property)) {
            $type = PDO::PARAM_BOOL;
        }

        return $type;
    }

    /**
     * Get select results as an array of Entities.
     *
     * @param string     $entity
     * @param array|null $rows
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    protected function getSelectResultsAsEntities(
        string $entity,
        array $rows = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        /** @var Entity|string $entity */

        $results = [];

        // Iterate through the rows found
        foreach ($rows as $row) {
            // Create a new entity
            /** @var Entity $entity */
            $rowEntity = $entity::fromArray($row);

            // If no columns were specified then we can safely get all the relations
            if (null === $columns && $getRelations === true) {
                // Add the model to the final results
                $results[] = $this->getEntityRelations($rowEntity);
            } else {
                // Add the model to the final results
                $results[] = $rowEntity;
            }
        }

        return $results;
    }

    /**
     * Build a select query statement by given criteria.
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
     * </code>.
     *
     * @param string     $entity
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return QueryBuilder
     */
    protected function selectQueryBuilder(
        string $entity,
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): QueryBuilder {
        /** @var Entity|string $entity */

        // Create a new query
        $query = $this
            ->getQueryBuilder()
            ->select($columns)
            ->table($entity::getTable());

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
            if (is_array($criterion)) {
                $this->setArrayCriterionInQuery($query, $column, $criterion);

                continue;
            }

            // If the criterion has a percent at the start or the end
            if ($criterion[0] === '%' || $criterion[strlen($criterion) - 1] === '%') {
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
        $lastIndex       = count($criterion) - 1;

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
     * <code>
     *      $this
     *          ->saveOrCreate(
     *             'update' | 'insert' | 'delete',
     *              Entity::class
     *          )
     * </code>.
     *
     * @param string $type
     * @param Entity $entity
     *
     * @throws InvalidArgumentException
     * @throws ExecuteException
     *
     * @return int
     */
    protected function saveCreateDelete(string $type, Entity $entity): int
    {
        if (! $this->store()->inTransaction()) {
            $this->store()->beginTransaction();
        }

        // Create a new query
        $query      = $this
            ->getQueryBuilder()
            ->table($entity::getTable())
            ->{$type}();
        $idField    = $entity::getIdField();
        $properties = $entity->forDataStore();

        /* @var QueryBuilder $query */

        // If this type isn't an insert
        if ($type !== self::INSERT) {
            // Set the id for the where clause
            $query->where($idField . ' = ' . $this->criterionParam($idField));
        }

        if ($type !== self::DELETE) {
            // Set the properties
            $this->setPropertiesForSaveCreateDeleteQuery($query, $properties);
        }

        // Prepare a PDO statement with the query
        $statement = $this->store()->prepare($query->getQuery());

        // If this type isn't an insert
        if ($type !== self::INSERT) {
            // Set the id value for the where clause
            $this->bindValue($statement, $this->criterionParam($idField), $properties[$idField]);
        }

        if ($type !== self::DELETE) {
            // Set the properties.
            $this->setPropertiesForSaveCreateDeleteStatement($statement, $properties);
        }

        // If the execute failed
        if (! $results = $statement->execute()) {
            // Throw a fail exception
            throw new ExecuteException($statement->errorInfo()[2]);
        }

        return $results;
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
            if (is_object($property)) {
                $property = serialize($property);
            } // Otherwise json encode if its an array
            elseif (is_array($property)) {
                $property = json_encode($property, JSON_THROW_ON_ERROR);
            }

            // Bind property
            $this->bindValue($statement, $column, $property);
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
            $entityName  = is_array($type) ? $type[0] : $type;
            $propertyMap = $propertyMapper[$property] ?? null;

            if (null !== $propertyMap && (is_array($type) || ! PropertyType::isValid($type))) {
                $repository   = $this->getRepository($entityName);
                $orderBy      = $propertyMap[PropertyMap::ORDER_BY] ?? null;
                $limit        = $propertyMap[PropertyMap::LIMIT] ?? null;
                $offset       = $propertyMap[PropertyMap::OFFSET] ?? null;
                $columns      = $propertyMap[PropertyMap::COLUMNS] ?? null;
                $getRelations = $propertyMap[PropertyMap::GET_RELATIONS] ?? true;

                unset(
                    $propertyMap[PropertyMap::ORDER_BY],
                    $propertyMap[PropertyMap::LIMIT],
                    $propertyMap[PropertyMap::OFFSET],
                    $propertyMap[PropertyMap::COLUMNS],
                    $propertyMap[PropertyMap::GET_RELATIONS]
                );

                $entities = $repository->findBy($propertyMap, $orderBy, $limit, $offset, $columns, $getRelations);

                if (is_array($type)) {
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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            EntityManager::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(EntityManager::class, new static($app));
    }
}
