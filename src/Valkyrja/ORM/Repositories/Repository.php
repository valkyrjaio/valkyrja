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

namespace Valkyrja\ORM\Repositories;

use InvalidArgumentException;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository as RepositoryContract;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\ClassHelpers;

use function get_class;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements RepositoryContract
{
    /**
     * The adapter name to use.
     *
     * @var string|null
     */
    protected static ?string $adapterName = null;

    /**
     * The connection name to use.
     *
     * @var string|null
     */
    protected static ?string $connectionName = null;

    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The connection.
     *
     * @var Connection
     */
    protected Connection $connection;

    /**
     * The entity manager.
     *
     * @var ORM
     */
    protected ORM $manager;

    /**
     * The persister.
     *
     * @var Persister
     */
    protected Persister $persister;

    /**
     * The retriever.
     *
     * @var Retriever
     */
    protected Retriever $retriever;

    /**
     * The entity to use.
     *
     * @var string|Entity
     */
    protected string $entity;

    /**
     * Whether to get relations.
     *
     * @var bool
     */
    protected bool $getRelations = false;

    /**
     * Repository constructor.
     *
     * @param ORM    $manager
     * @param string $entity
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ORM $manager, string $entity)
    {
        ClassHelpers::validateClass($entity, Entity::class);

        $this->adapter    = $manager->getAdapter(static::$adapterName);
        $this->connection = $this->adapter->getConnection(static::$connectionName);
        $this->persister  = $this->connection->getPersister();
        $this->manager    = $manager;
        $this->entity     = $entity;
    }

    /**
     * Make a new repository.
     *
     * @param ORM    $manager
     * @param string $entity
     *
     * @return static
     */
    public static function make(ORM $manager, string $entity): self
    {
        return new static($manager, $entity);
    }

    /**
     * Set the adapter to use.
     *
     * @param string $adapter
     *
     * @return static
     */
    public function setAdapter(string $adapter): self
    {
        $this->adapter    = $this->manager->getAdapter($adapter);
        $this->connection = $this->adapter->getConnection();
        $this->persister  = $this->connection->getPersister();

        return $this;
    }

    /**
     * Get the connection.
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Set the connection to use.
     *
     * @param string $connection
     *
     * @return static
     */
    public function setConnection(string $connection): self
    {
        $this->connection = $this->adapter->getConnection($connection);
        $this->persister  = $this->connection->getPersister();

        return $this;
    }

    /**
     * Find by given criteria.
     *
     * @param bool|null $getRelations
     *
     * @return static
     */
    public function find(bool $getRelations = false): self
    {
        $this->retriever    = $this->connection->createRetriever()->find($this->entity, $getRelations);
        $this->getRelations = $getRelations;

        return $this;
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return static
     */
    public function findOne($id, bool $getRelations = false): self
    {
        $this->retriever    = $this->connection->createRetriever()->findOne($this->entity, $id, $getRelations);
        $this->getRelations = $getRelations;

        return $this;
    }

    /**
     * Count all the results of given criteria.
     *
     * @return static
     */
    public function count(): self
    {
        $this->retriever    = $this->connection->createRetriever()->count($this->entity);
        $this->getRelations = false;

        return $this;
    }

    /**
     * Set columns.
     *
     * @param array $columns
     *
     * @return static
     */
    public function columns(array $columns): self
    {
        $this->retriever->columns($columns);

        return $this;
    }

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function where(string $column, string $operator = null, $value = null): self
    {
        $this->retriever->where($column, $operator, $value);

        return $this;
    }

    /**
     * Add an additional `OR` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function orWhere(string $column, string $operator = null, $value = null): self
    {
        $this->retriever->orWhere($column, $operator, $value);

        return $this;
    }

    /**
     * Set an order by.
     *
     * @param string      $column
     * @param string|null $type
     *
     * @return static
     */
    public function orderBy(string $column, string $type = null): self
    {
        $this->retriever->orderBy($column, $type);

        return $this;
    }

    /**
     * Set limit.
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): self
    {
        $this->retriever->limit($limit);

        return $this;
    }

    /**
     * Set offset.
     *
     * @param int $offset
     *
     * @return static
     */
    public function offset(int $offset): self
    {
        $this->retriever->offset($offset);

        return $this;
    }

    /**
     * Get results.
     *
     * @return Entity[]
     */
    public function getResult(): array
    {
        return $this->retriever->getResult();
    }

    /**
     * Get one or null.
     *
     * @return Entity|null
     */
    public function getOneOrNull(): ?Entity
    {
        return $this->retriever->getOneOrNull();
    }

    /**
     * Get one or fail.
     *
     * @throws EntityNotFoundException
     *
     * @return Entity
     */
    public function getOneOrFail(): Entity
    {
        return $this->retriever->getOneOrFail();
    }

    /**
     * Get count results.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->retriever->getCount();
    }

    /**
     * Create a new entity.
     *
     * <code>
     *      $repository->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->create($entity, $defer);
    }

    /**
     * Update an existing entity.
     *
     * <code>
     *      $repository->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->save($entity, $defer);
    }

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $repository->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->delete($entity, $defer);
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
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->softDelete($entity, $defer);
    }

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $repository->clear(new Entity())
     * </code>
     *
     * @param Entity $entity
     *
     * @throws InvalidEntityException
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity !== null) {
            $this->validateEntity($entity);
        }

        $this->persister->clear($entity);
    }

    /**
     * Persist all entities.
     *
     * @return bool
     */
    public function persist(): bool
    {
        return $this->persister->persist();
    }

    /**
     * Get a new query builder instance.
     *
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder
    {
        return $this->connection->createQueryBuilder($this->entity, $alias);
    }

    /**
     * Create a new query.
     *
     * @param string $query
     *
     * @return Query
     */
    public function createQuery(string $query): Query
    {
        return $this->connection->createQuery($query, $this->entity);
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
                . get_class($entity)
                . ' provided instead.'
            );
        }
    }
}
