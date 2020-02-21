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

namespace Valkyrja\ORM\EntityPersisters;

use function get_class;
use function is_array;
use function is_object;
use const JSON_THROW_ON_ERROR;
use PDO;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\EntityPersister;
use Valkyrja\ORM\Enums\Statement;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;

/**
 * Class PDOEntityPersister
 *
 * @author Melech Mizrachi
 */
class PDOEntityPersister implements EntityPersister
{
    /**
     * The connection.
     *
     * @var PDO
     */
    protected PDO $connection;

    /**
     * The entity manager.
     *
     * @var EntityManager
     */
    protected EntityManager $entityManager;

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
     * PDOEntityPersister constructor.
     *
     * @param EntityManager $entityManager
     * @param PDO           $connection
     */
    public function __construct(EntityManager $entityManager, PDO $connection)
    {
        $this->entityManager = $entityManager;
        $this->connection    = $connection;
    }

    /**
     * Set a model for creation on transaction commit.
     * <code>
     *      $entityPersister
     *          ->create(
     *              new Entity()
     *          )
     * </code>.
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
     * <code>
     *      $entityPersister
     *          ->save(
     *              new Entity()
     *          )
     * </code>.
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
     * <code>
     *      $entityPersister
     *          ->delete(
     *              new Entity()
     *          )
     * </code>.
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
     * Clear a model previously set for creation, save, or deletion.
     * <code>
     *      $entityPersister
     *          ->clear(
     *              new Entity()
     *          )
     * </code>.
     *
     * @param Entity|null $entity The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity === null) {
            $this->createEntities = [];
            $this->saveEntities   = [];
            $this->deleteEntities = [];

            return;
        }

        // Get the id of the object
        $id = spl_object_id($entity);

        // If the model is set to be created
        if (isset($this->createEntities[$id])) {
            // Unset it
            unset($this->createEntities[$id]);

            return;
        }

        // If the model is set to be saved
        if (isset($this->saveEntities[$id])) {
            // Unset it
            unset($this->saveEntities[$id]);

            return;
        }

        // If the model is set to be deleted
        if (isset($this->deleteEntities[$id])) {
            // Unset it
            unset($this->deleteEntities[$id]);

            return;
        }
    }

    /**
     * Persist all entities.
     *
     * @throws ExecuteException
     *
     * @return void
     */
    public function persist(): void
    {
        // Iterate through the models awaiting creation
        foreach ($this->createEntities as $cid => $createEntity) {
            // Create the model
            $this->saveCreateDelete(Statement::UPDATE, $createEntity);
            // Unset the model
            unset($this->createEntities[$cid]);
        }

        // Iterate through the models awaiting save
        foreach ($this->saveEntities as $sid => $saveEntity) {
            // Save the model
            $this->saveCreateDelete(Statement::INSERT, $saveEntity);
            // Unset the model
            unset($this->saveEntities[$sid]);
        }

        // Iterate through the models awaiting deletion
        foreach ($this->deleteEntities as $sid => $deleteEntity) {
            // Save the model
            $this->saveCreateDelete(Statement::DELETE, $deleteEntity);
            // Unset the model
            unset($this->deleteEntities[$sid]);
        }
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
     * @throws ExecuteException
     *
     * @return void
     */
    protected function saveCreateDelete(string $type, Entity $entity): void
    {
        if (! $this->connection->inTransaction()) {
            $this->connection->beginTransaction();
        }

        $idField    = $entity::getIdField();
        $properties = $entity->forDataStore();

        // Get the query builder
        $queryBuilder = $this->getQueryBuilderForSaveCreateDelete($type, $entity, $idField, $properties);

        // Create a new query with the query builder
        $query = $this->entityManager->query($queryBuilder->getQueryString(), get_class($entity));

        // Bind values
        $this->bindValuesForSaveCreateDelete($query, $type, $idField, $properties);

        // If the execute failed
        if (! $results = $query->execute()) {
            // Throw a fail exception
            throw new ExecuteException($query->getError());
        }

        $entity->{$idField} = $this->entityManager->lastInsertId();
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
        $queryBuilder = $this->entityManager->queryBuilder(get_class($entity))->{strtolower($type)}();

        /* @var QueryBuilder $queryBuilder */

        // If this type isn't an insert
        if ($type !== Statement::INSERT) {
            // Set the id for the where clause
            $queryBuilder->where($idField . ' = ' . $this->columnParam($idField));
        }

        if ($type !== Statement::DELETE) {
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
        if ($type !== Statement::INSERT) {
            // Set the id value for the where clause
            $query->bindValue($idField, $properties[$idField]);
        }

        if ($type !== Statement::DELETE) {
            // Set the properties.
            $this->setPropertiesForSaveCreateDeleteStatement($query, $properties);
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
}
