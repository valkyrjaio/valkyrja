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
use Valkyrja\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Enums\PropertyMap;
use Valkyrja\ORM\Enums\PropertyType;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\Queries\PDOQuery;
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
     * The connection to use.
     *
     * @var string
     */
    protected string $connection;

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected array $repositories = [];

    /**
     * Connections.
     *
     * @var PDO[]
     */
    protected static array $connections = [];

    /**
     * The entities awaiting to be committed for creation.
     *
     * @var Entity[]
     */
    protected array $createEntities = [];

    /**
     * The entities awaiting to be committed for saving.
     *
     * @var Entity[]
     */
    protected array $saveEntities = [];

    /**
     * The entities awaiting to be committed for deletion.
     *
     * @var Entity[]
     */
    protected array $deleteEntities = [];

    /**
     * PDOEntityManager constructor.
     *
     * @param Application $app
     * @param string|null $connection
     */
    public function __construct(Application $app, string $connection = null)
    {
        $this->app        = $app;
        $this->connection = $connection ?? $app->config()[ConfigKeyPart::DB][ConfigKeyPart::DEFAULT];

        $this->connection()->beginTransaction();
    }

    /**
     * Get a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function queryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        $queryBuilder = new SqlQueryBuilder($this->connection());

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }

    /**
     * Start a query.
     *
     * @param string      $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function query(string $query, string $entity = null): Query
    {
        $pdoQuery = new PDOQuery($this->connection());

        if (null !== $entity) {
            $pdoQuery->entity($entity);
        }

        return $pdoQuery->prepare($query);
    }

    /**
     * Get a repository instance.
     *
     * @param string $entity
     *
     * @return Repository
     */
    public function repository(string $entity): Repository
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
        return $this->connection()->beginTransaction();
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

        return $this->connection()->commit();
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
        return $this->connection()->rollBack();
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->connection()->lastInsertId();
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
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @throws InvalidArgumentException
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
    ): Entity {
        return $this->select($entity, $columns, $criteria, $orderBy, 1, $offset, $getRelations)[0];
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
     * @return PDO
     */
    protected function connection(): PDO
    {
        if (isset(self::$connections[$this->connection])) {
            return self::$connections[$this->connection];
        }

        $config = $this->getConnectionConfig($this->connection);

        return self::$connections[$this->connection] = $this->getConnectionFromConfig($config);
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
    protected function getConnectionConfig(string $name): array
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
    protected function getConnectionFromConfig(array $config): PDO
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
     * Select entities by given criteria.
     * <code>
     *      $this
     *          ->select(
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
        // Get the query builders
        $queryBuilder = $this->getQueryBuilderForSelect($entity, $columns, $criteria, $orderBy, $limit, $offset);

        // Create a new query with the query builder
        $query = $this->query($queryBuilder->getQueryString(), $entity);

        // Bind criteria
        $this->bindValuesForSelect($query, $criteria);

        // Execute the query
        $query->execute();

        // Get all the results from the query
        $result = $query->getResult();

        // If the results are an array (not a count result [int])
        if (is_array($result)) {
            // Try to get the entity relations
            $this->selectResultsRelations($result, $columns, $getRelations);
        }

        return $result;
    }

    /**
     * Bind criteria for a select statement.
     *
     * @param Query      $query
     * @param array|null $criteria
     *
     * @return void
     */
    protected function bindValuesForSelect(Query $query, array $criteria = null): void
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
                    $query->bindValue($column . $index, $criterionItem);
                }

                continue;
            }

            // And bind each value to the column
            $query->bindValue($column, $criterion);
        }
    }

    /**
     * Get select results as an array of Entities.
     *
     * @param Entity[]   $entities
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return void
     */
    protected function selectResultsRelations(array $entities, array $columns = null, bool $getRelations = null): void
    {
        // Iterate through the rows found
        foreach ($entities as $entity) {
            // If no columns were specified then we can safely get all the relations
            if (null === $columns && $getRelations === true) {
                // Add the model to the final results
                $this->getEntityRelations($entity);
            }
        }
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
    protected function getQueryBuilderForSelect(
        string $entity,
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): QueryBuilder {
        /** @var Entity|string $entity */

        // Create a new query
        $query = $this->queryBuilder($entity)->select($columns);

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
     *          ->saveCreateDelete(
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
        if (! $this->connection()->inTransaction()) {
            $this->connection()->beginTransaction();
        }

        $idField    = $entity::getIdField();
        $properties = $entity->forDataStore();

        // Get the query builder
        $queryBuilder = $this->getQueryBuilderForSaveCreateDelete($type, $entity, $idField, $properties);

        // Create a new query with the query builder
        $query = $this->query($queryBuilder->getQueryString(), $entity);

        // Bind values
        $this->bindValuesForSaveCreateDelete($query, $type, $idField, $properties);

        // If the execute failed
        if (! $results = $query->execute()) {
            // Throw a fail exception
            throw new ExecuteException($query->getError());
        }

        return $results;
    }

    /**
     * Get the query builder for a save, create, or delete.
     *
     * @param string $type
     * @param Entity $entity
     * @param string $idField
     * @param array  $properties
     *
     * @return QueryBuilder
     */
    protected function getQueryBuilderForSaveCreateDelete(
        string $type,
        Entity $entity,
        string $idField,
        array $properties
    ): QueryBuilder {
        // Create a new query
        $queryBuilder = $this->queryBuilder($entity)->{$type}();

        /* @var QueryBuilder $queryBuilder */

        // If this type isn't an insert
        if ($type !== self::INSERT) {
            // Set the id for the where clause
            $queryBuilder->where($idField . ' = ' . $this->columnParam($idField));
        }

        if ($type !== self::DELETE) {
            // Set the properties
            $this->setPropertiesForSaveCreateDeleteQuery($queryBuilder, $properties);
        }

        return $queryBuilder;
    }

    /**
     * Bind values for save, create, or delete.
     *
     * @param Query  $query
     * @param string $type
     * @param string $idField
     * @param array  $properties
     *
     * @return void
     */
    protected function bindValuesForSaveCreateDelete(
        Query $query,
        string $type,
        string $idField,
        array $properties
    ): void {
        // If this type isn't an insert
        if ($type !== self::INSERT) {
            // Set the id value for the where clause
            $query->bindValue($idField, $properties[$idField]);
        }

        if ($type !== self::DELETE) {
            // Set the properties.
            $this->setPropertiesForSaveCreateDeleteStatement($query, $properties);
        }
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
     * @param Query $query
     * @param array $properties
     *
     * @return void
     */
    protected function setPropertiesForSaveCreateDeleteStatement(Query $query, array $properties): void
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
            $query->bindValue($column, $property);
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
                $repository   = $this->repository($entityName);
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
