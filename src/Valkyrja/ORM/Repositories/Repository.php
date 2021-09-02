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
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository as Contract;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\Type\Cls;
use Valkyrja\Support\Type\Str;

use function get_class;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 */
class Repository implements Contract
{
    /**
     * The connection driver.
     *
     * @var Driver
     */
    protected Driver $driver;

    /**
     * The entity manager.
     *
     * @var ORM
     */
    protected ORM $orm;

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
     * The relationships to get with each result.
     *
     * @var string[]|null
     */
    protected ?array $relationships = null;

    /**
     * Whether to get relations.
     *
     * @var bool
     */
    protected bool $getRelations = false;

    /**
     * Repository constructor.
     *
     * @param ORM    $manager The orm manager
     * @param Driver $driver  The driver
     * @param string $entity  The entity class name
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ORM $manager, Driver $driver, string $entity)
    {
        Cls::validateInherits($entity, Entity::class);

        $this->driver    = $driver;
        $this->persister = $this->driver->getPersister();
        $this->orm       = $manager;
        $this->entity    = $entity;
    }

    /**
     * Find by given criteria.
     *
     * @return static
     */
    public function find(): self
    {
        $this->retriever = $this->driver->createRetriever()->find($this->entity);
        $this->resetRelationships();

        return $this;
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id The id
     *
     * @return static
     */
    public function findOne($id): self
    {
        $this->retriever = $this->driver->createRetriever()->findOne($this->entity, $id);
        $this->resetRelationships();

        return $this;
    }

    /**
     * Count all the results of given criteria.
     *
     * @return static
     */
    public function count(): self
    {
        $this->retriever = $this->driver->createRetriever()->count($this->entity);
        $this->resetRelationships();

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
     * Join with another table.
     *
     * @param string      $table    The table to join on
     * @param string      $column1  The column to join on
     * @param string      $column2  The secondary column to join on
     * @param string|null $operator [optional] The operator
     * @param string|null $type     [optional] The type of join
     * @param bool|null   $isWhere  [optional] Whether this is a where join
     *
     * @return static
     */
    public function join(
        string $table,
        string $column1,
        string $column2,
        string $operator = null,
        string $type = null,
        bool $isWhere = null
    ): self {
        $this->retriever->join($table, $column1, $column2, $operator, $type, $isWhere);

        return $this;
    }

    /**
     * Set an order by.
     *
     * @param string      $column
     * @param string|null $direction
     *
     * @return static
     */
    public function orderBy(string $column, string $direction = null): self
    {
        $this->retriever->orderBy($column, $direction);

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
     * Add relationships to include with the results.
     *
     * @param array|null $relationships [optional] The relationships to get
     *
     * @return static
     */
    public function withRelationships(array $relationships = null): self
    {
        $this->getRelations  = true;
        $this->relationships = $relationships;

        return $this;
    }

    /**
     * Get results.
     *
     * @return Entity[]
     */
    public function getResult(): array
    {
        $results = $this->retriever->getResult();

        $this->setRelationshipsOnEntities(...$results);

        return $results;
    }

    /**
     * Get one or null.
     *
     * @return Entity|null
     */
    public function getOneOrNull(): ?Entity
    {
        return $this->getResult()[0] ?? null;
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
     * @param Entity|null $entity The entity
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
     * @param string|null $alias The alias to use
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder
    {
        return $this->driver->createQueryBuilder($this->entity, $alias);
    }

    /**
     * Create a new query.
     *
     * @param string $query The query string
     *
     * @return Query
     */
    public function createQuery(string $query): Query
    {
        return $this->driver->createQuery($query, $this->entity);
    }

    /**
     * Validate the passed entity.
     *
     * @param Entity $entity The entity
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

    /**
     * Reset the relationship properties.
     *
     * @return void
     */
    protected function resetRelationships(): void
    {
        $this->getRelations  = false;
        $this->relationships = null;
    }

    /**
     * Set relationships on the entities from results.
     *
     * @param Entity ...$entities The entities to add relationships to
     *
     * @return void
     */
    protected function setRelationshipsOnEntities(Entity ...$entities): void
    {
        $relationships = $this->relationships;

        if (empty($relationships) || ! $this->getRelations || empty($entities)) {
            return;
        }

        // Iterate through the rows found
        foreach ($entities as $entity) {
            $relationships = $relationships ?? $entity::getRelationshipProperties();
            // Get the entity relations
            $this->setRelationshipsOnEntity($relationships, $entity);
        }
    }

    /**
     * Set relationships on an entity.
     *
     * @param array  $relationships The relationships to set
     * @param Entity $entity        The entity
     *
     * @return void
     */
    protected function setRelationshipsOnEntity(array $relationships, Entity $entity): void
    {
        // Iterate through the rows found
        foreach ($relationships as $relationship) {
            // Set the entity relations
            $this->setRelationship($entity, $relationship);
        }
    }

    /**
     * Set a relationship property.
     *
     * @param Entity $entity       The entity
     * @param string $relationship The relationship to set
     *
     * @return void
     */
    public function setRelationship(Entity $entity, string $relationship): void
    {
        $methodName = 'set' . Str::toStudlyCase($relationship) . 'Relationship';

        if (method_exists($this, $methodName)) {
            $this->$methodName($entity);
        }
    }
}
