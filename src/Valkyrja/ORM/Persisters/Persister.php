<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Persisters;

use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Constants\Statement;
use Valkyrja\ORM\DatedEntity;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\Persister as PersisterContract;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\SoftDeleteEntity;

use function date;
use function is_array;
use function is_object;
use function json_encode;
use function serialize;
use function spl_object_id;
use function strtolower;

use const JSON_THROW_ON_ERROR;

/**
 * Class Persister
 *
 * @author Melech Mizrachi
 */
class Persister implements PersisterContract
{
    /**
     * The entity manager.
     *
     * @var Connection
     */
    protected Connection $connection;

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
     * Persister constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Create a new entity.
     *
     * <code>
     *      $persister->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws ExecuteException
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        if ($entity instanceof DatedEntity) {
            $entity->setCreatedAtFieldValue($this->getFormattedDate());
            $entity->setUpdatedAtFieldValue($this->getFormattedDate());
        }

        if (! $defer) {
            $this->persistEntity(Statement::UPDATE, $entity);

            return;
        }

        $id = spl_object_id($entity);

        $this->createEntities[$id] = $entity;
    }

    /**
     * Update an existing entity.
     *
     * <code>
     *      $persister->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws ExecuteException
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        if ($entity instanceof DatedEntity) {
            $entity->setUpdatedAtFieldValue($this->getFormattedDate());
        }

        if (! $defer) {
            $this->persistEntity(Statement::INSERT, $entity);

            return;
        }

        $id = spl_object_id($entity);

        $this->saveEntities[$id] = $entity;
    }

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $persister->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws ExecuteException
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        if (! $defer) {
            $this->persistEntity(Statement::DELETE, $entity);

            return;
        }

        $id = spl_object_id($entity);

        $this->deleteEntities[$id] = $entity;
    }

    /**
     * Soft delete an existing entity.
     *
     * <code>
     *      $persister->softDelete(new SoftDeleteEntity(), true | false)
     * </code>
     *
     * @param SoftDeleteEntity $entity
     * @param bool             $defer [optional]
     *
     * @throws ExecuteException
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $entity->setDeletedFieldValue(true);
        $entity->setDeletedAtFieldValue($this->getFormattedDate());

        $this->save($entity, $defer);
    }

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $persister->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity === null) {
            $this->clearDeferred();

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
     * @return bool
     */
    public function persist(): bool
    {
        // Ensure a transaction is in progress
        $this->connection->ensureTransaction();

        $this->persistCreate();
        $this->persistSave();
        $this->persistDelete();
        $this->clearDeferred();

        return $this->connection->commit();
    }

    /**
     * Get the formatted date.
     *
     * @return string
     */
    protected function getFormattedDate(): string
    {
        return date('y-m-d H:i:s');
    }

    /**
     * Save or create or delete a row.
     *
     * <code>
     *      $this
     *          ->saveCreateDelete(
     *             'update' | 'insert' | 'delete',
     *              Entity::class
     *          )
     * </code>
     *
     * @param string $type
     * @param Entity $entity
     *
     * @throws ExecuteException
     *
     * @return void
     */
    protected function persistEntity(string $type, Entity $entity): void
    {
        $idField    = $entity::getIdField();
        $properties = $entity->forDataStore();

        // Get the query builder
        $queryBuilder = $this->getQueryBuilder($type, $entity, $properties);
        // Get the query
        $query = $this->getQuery($queryBuilder, $type, $idField, $properties);

        // If the execute failed
        if (! $query->execute()) {
            // Throw a fail exception
            throw new ExecuteException($query->getError());
        }

        $entity->setIdFieldValue($this->connection->lastInsertId());
    }

    /**
     * Get the query builder.
     *
     * @param string $type
     * @param Entity $entity
     * @param array  $properties
     *
     * @return QueryBuilder
     */
    protected function getQueryBuilder(string $type, Entity $entity, array $properties): QueryBuilder
    {
        // Create a new query
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->table($entity::getEntityTable());
        $queryBuilder->{strtolower($type)}();

        // If this type isn't an insert
        if ($type !== Statement::INSERT) {
            // Set the id for the where clause
            $queryBuilder->where($entity::getIdField());
        }

        if ($type !== Statement::DELETE) {
            // Set the properties
            $this->setQueryBuilderProperties($queryBuilder, $properties);
        }

        return $queryBuilder;
    }

    /**
     * Get the query.
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $type
     * @param string       $idField
     * @param array        $properties
     *
     * @return Query
     */
    protected function getQuery(QueryBuilder $queryBuilder, string $type, string $idField, array $properties): Query
    {
        // Create a new query with the query builder
        $query = $this->connection->createQuery($queryBuilder->getQueryString());

        // If this type isn't an insert
        if ($type !== Statement::INSERT) {
            // Set the id value for the where clause
            $query->bindValue($idField, $properties[$idField]);
        }

        if ($type !== Statement::DELETE) {
            // Set the properties.
            $this->bindQueryProperties($query, $properties);
        }

        return $query;
    }

    /**
     * Set properties for save, delete, or create queries.
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $properties
     *
     * @return void
     */
    protected function setQueryBuilderProperties(QueryBuilder $queryBuilder, array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $column => $property) {
            if ($property === null) {
                continue;
            }

            // Set the column and param name
            $queryBuilder->set($column);
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
    protected function bindQueryProperties(Query $query, array $properties): void
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
     * Clear deferred entities.
     *
     * @return void
     */
    protected function clearDeferred(): void
    {
        $this->createEntities = [];
        $this->saveEntities   = [];
        $this->deleteEntities = [];
    }

    /**
     * Persist all entities for creation.
     *
     * @throws ExecuteException
     *
     * @return void
     */
    protected function persistCreate(): void
    {
        // Iterate through the models awaiting creation
        foreach ($this->createEntities as $createEntity) {
            // Create the model
            $this->persistEntity(Statement::UPDATE, $createEntity);
        }
    }

    /**
     * Persist all entities for save.
     *
     * @throws ExecuteException
     *
     * @return void
     */
    protected function persistSave(): void
    {
        // Iterate through the models awaiting save
        foreach ($this->saveEntities as $saveEntity) {
            // Save the model
            $this->persistEntity(Statement::INSERT, $saveEntity);
        }
    }

    /**
     * Persist all entities for deletion.
     *
     * @throws ExecuteException
     *
     * @return void
     */
    protected function persistDelete(): void
    {
        // Iterate through the models awaiting deletion
        foreach ($this->deleteEntities as $deleteEntity) {
            // delete the model
            $this->persistEntity(Statement::DELETE, $deleteEntity);
        }
    }
}
