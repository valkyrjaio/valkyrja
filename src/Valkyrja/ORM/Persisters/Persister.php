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

use JsonException;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Constants\Statement;
use Valkyrja\ORM\DatedEntity;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\ExecuteException;
use Valkyrja\ORM\Persister as Contract;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\ORM\Support\Helpers;
use Valkyrja\Support\Type\Arr;

use function is_array;
use function is_object;
use function serialize;
use function spl_object_id;
use function strtolower;

/**
 * Class Persister
 *
 * @author Melech Mizrachi
 */
class Persister implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

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
     * @param Adapter $connection The adapter
     */
    public function __construct(Adapter $connection)
    {
        $this->adapter = $connection;
    }

    /**
     * @inheritDoc
     *
     * @throws ExecuteException
     * @throws JsonException
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        if ($entity instanceof DatedEntity) {
            $date = Helpers::getFormattedDate();

            $entity->__set($entity::getDateCreatedField(), $date);
            $entity->__set($entity::getDateModifiedField(), $date);
        }

        if (! $defer) {
            // Ensure a transaction is in progress
            $this->adapter->ensureTransaction();

            $this->persistEntity(Statement::INSERT, $entity, $entity->asStorableArray());

            $this->adapter->commit();

            return;
        }

        $id = spl_object_id($entity);

        $this->createEntities[$id] = $entity;
    }

    /**
     * @inheritDoc
     *
     * @throws ExecuteException
     * @throws JsonException
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        if ($entity instanceof DatedEntity) {
            $entity->__set($entity::getDateModifiedField(), Helpers::getFormattedDate());
        }

        if (! $defer) {
            // Ensure a transaction is in progress
            $this->adapter->ensureTransaction();

            $this->persistEntity(Statement::UPDATE, $entity, $entity->asChangedArray());

            $this->adapter->commit();

            return;
        }

        $id = spl_object_id($entity);

        $this->saveEntities[$id] = $entity;
    }

    /**
     * @inheritDoc
     *
     * @throws ExecuteException
     * @throws JsonException
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        if (! $defer) {
            // Ensure a transaction is in progress
            $this->adapter->ensureTransaction();

            $this->persistEntity(Statement::DELETE, $entity);

            $this->adapter->commit();

            return;
        }

        $id = spl_object_id($entity);

        $this->deleteEntities[$id] = $entity;
    }

    /**
     * @inheritDoc
     *
     * @throws ExecuteException
     * @throws JsonException
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $entity->__set($entity::getIsDeletedField(), true);
        $entity->__set($entity::getDateDeletedField(), Helpers::getFormattedDate());

        $this->save($entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity === null) {
            $this->clearDeferred();

            return;
        }

        $this->clearEntity($entity);
    }

    /**
     * @inheritDoc
     *
     * @throws ExecuteException
     * @throws JsonException
     */
    public function persist(): bool
    {
        // Ensure a transaction is in progress
        $this->adapter->ensureTransaction();

        $this->persistCreate();
        $this->persistSave();
        $this->persistDelete();
        $this->clearDeferred();

        return $this->adapter->commit();
    }

    /**
     * Clear a single deferred entity.
     *
     * @param Entity $entity The entity instance to remove.
     *
     * @return void
     */
    protected function clearEntity(Entity $entity): void
    {
        // Get the id of the object
        $id = spl_object_id($entity);

        // Unset it
        unset(
            $this->createEntities[$id],
            $this->saveEntities[$id],
            $this->deleteEntities[$id]
        );
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
     * @param string $type       The type of persist
     * @param Entity $entity     The entity to persist
     * @param array  $properties [optional] The properties to persist
     *
     * @throws ExecuteException
     * @throws JsonException
     *
     * @return void
     */
    protected function persistEntity(string $type, Entity $entity, array $properties = []): void
    {
        $idField = $entity::getIdField();

        // Get the query builder
        $queryBuilder = $this->getQueryBuilder($type, $entity, $properties);
        // Get the query
        $query = $this->getQuery($queryBuilder, $type, $idField, $entity->{$idField}, $properties);

        // If the execute failed
        if (! $query->execute()) {
            // Throw a fail exception
            throw new ExecuteException($query->getError());
        }

        if (
            ! $entity->__isset($idField)
            && $lastInsertId = $this->adapter->lastInsertId($entity::getTableName(), $idField)
        ) {
            $entity->__set($idField, $lastInsertId);
        }
    }

    /**
     * Get the query builder.
     *
     * @param string $type       The type of persist
     * @param Entity $entity     The entity to persist
     * @param array  $properties The properties to persist
     *
     * @return QueryBuilder
     */
    protected function getQueryBuilder(string $type, Entity $entity, array $properties): QueryBuilder
    {
        // Create a new query
        $queryBuilder = $this->adapter->createQueryBuilder();

        $queryBuilder->table($entity::getTableName());
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
     * @param QueryBuilder $queryBuilder The query builder
     * @param string       $type         The type of persist
     * @param string       $idField      The id field
     * @param string|int   $id           The id
     * @param array        $properties   The properties to persist
     *
     * @throws JsonException
     *
     * @return Query
     */
    protected function getQuery(
        QueryBuilder $queryBuilder,
        string $type,
        string $idField,
        $id,
        array $properties
    ): Query {
        // Create a new query with the query builder
        $query = $this->adapter->createQuery($queryBuilder->getQueryString());

        // If this type isn't an insert
        if ($type !== Statement::INSERT) {
            // Set the id value for the where clause
            $query->bindValue($idField, $id);
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
     * @param QueryBuilder $queryBuilder The query builder
     * @param array        $properties   The properties to persist
     *
     * @return void
     */
    protected function setQueryBuilderProperties(QueryBuilder $queryBuilder, array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $column => $value) {
            $this->setQueryBuilderProperty($queryBuilder, $column, $value);
        }
    }

    /**
     * Set properties for save, delete, or create queries.
     *
     * @param QueryBuilder $queryBuilder The query builder
     * @param string       $column       The column name
     * @param mixed        $value        The property value
     *
     * @return void
     */
    protected function setQueryBuilderProperty(QueryBuilder $queryBuilder, string $column, $value): void
    {
        // Set the column and param name
        $queryBuilder->set($column);
    }

    /**
     * Set properties for save, create, or delete statements.
     *
     * @param Query $query      The query
     * @param array $properties The properties to persist
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function bindQueryProperties(Query $query, array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $column => $value) {
            $this->bindQueryProperty($query, $column, $value);
        }
    }

    /**
     * Set property for save, create, or delete statements.
     *
     * @param Query  $query  The query
     * @param string $column The column name
     * @param mixed  $value  The property value
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function bindQueryProperty(Query $query, string $column, $value): void
    {
        // If the property is an object, then serialize it
        if (is_object($value)) {
            $value = serialize($value);
        } // Otherwise json encode if its an array
        elseif (is_array($value)) {
            $value = Arr::toString($value);
        }

        // Bind property
        $query->bindValue($column, $value);
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
     * @throws JsonException
     *
     * @return void
     */
    protected function persistCreate(): void
    {
        // Iterate through the models awaiting creation
        foreach ($this->createEntities as $createEntity) {
            // Create the model
            $this->persistEntity(Statement::INSERT, $createEntity, $createEntity->asStorableArray());
        }
    }

    /**
     * Persist all entities for save.
     *
     * @throws ExecuteException
     * @throws JsonException
     *
     * @return void
     */
    protected function persistSave(): void
    {
        // Iterate through the models awaiting save
        foreach ($this->saveEntities as $saveEntity) {
            // Save the model
            $this->persistEntity(Statement::UPDATE, $saveEntity, $saveEntity->asChangedArray());
        }
    }

    /**
     * Persist all entities for deletion.
     *
     * @throws ExecuteException
     * @throws JsonException
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
