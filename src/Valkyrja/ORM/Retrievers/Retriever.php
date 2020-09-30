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

namespace Valkyrja\ORM\Retrievers;

use InvalidArgumentException;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever as Contract;
use Valkyrja\Support\Type\Cls;

use function is_array;
use function is_int;
use function is_string;

/**
 * Class Retriever
 *
 * @author Melech Mizrachi
 */
class Retriever implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The query builder.
     *
     * @var QueryBuilder
     */
    protected QueryBuilder $queryBuilder;

    /**
     * The query.
     *
     * @var Query
     */
    protected Query $query;

    /**
     * The relationships to get with each result.
     *
     * @var string[]|null
     */
    protected ?array $relationships = null;

    /**
     * The values to bind.
     *
     * @var array
     */
    protected array $values = [];

    /**
     * Whether to get relations.
     *
     * @var bool
     */
    protected bool $getRelations = false;

    /**
     * Retriever constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Find by given criteria.
     *
     * <code>
     *      $retriever->find(Entity::class, true | false)
     * </code>
     *
     * @param string $entity The entity
     *
     * @return static
     */
    public function find(string $entity): self
    {
        $this->setQueryProperties($entity);

        return $this;
    }

    /**
     * Find a single entity given its id.
     *
     * <code>
     *      $retriever->findOne(Entity::class, 1, true | false)
     * </code>
     *
     * @param string     $entity The entity
     * @param string|int $id     The id to find
     *
     * @return static
     */
    public function findOne(string $entity, $id): self
    {
        $this->validateId($id);
        $this->setQueryProperties($entity);
        $this->limit(1);

        /** @var Entity $entity */
        $this->where($entity::getIdField(), null, $id);

        return $this;
    }

    /**
     * Count all the results of given criteria.
     *
     * <code>
     *      $retriever->count(Entity::class)
     * </code>
     *
     * @param string $entity The entity
     *
     * @return static
     */
    public function count(string $entity): self
    {
        $this->setQueryProperties($entity, ['COUNT(*)']);

        return $this;
    }

    /**
     * Set columns.
     *
     * @param array $columns The columns to return
     *
     * @return static
     */
    public function columns(array $columns): self
    {
        $this->queryBuilder = $this->queryBuilder->select($columns);

        return $this;
    }

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string      $column   The column
     * @param string|null $operator [optional] The operator
     * @param mixed|null  $value    [optional] The value to find
     *
     * @return static
     */
    public function where(string $column, string $operator = null, $value = null): self
    {
        $this->queryBuilder->where($column, $operator);
        $this->setValue($column, $value);

        return $this;
    }

    /**
     * Add an additional `OR` where condition.
     *
     * @param string      $column   The column
     * @param string|null $operator [optional] The operator
     * @param mixed|null  $value    [optional] The value to find
     *
     * @return static
     */
    public function orWhere(string $column, string $operator = null, $value = null): self
    {
        $this->queryBuilder->orWhere($column, $operator);
        $this->setValue($column, $value);

        return $this;
    }

    /**
     * Set group by.
     *
     * @param string $column The column to group by
     *
     * @return static
     */
    public function groupBy(string $column): self
    {
        $this->queryBuilder->groupBy($column);

        return $this;
    }

    /**
     * Set order by.
     *
     * @param string      $column The column to order by
     * @param string|null $type   [optional] Ascending or descending order by type
     *
     * @return static
     */
    public function orderBy(string $column, string $type = null): self
    {
        $this->queryBuilder->orderBy($column, $type);

        return $this;
    }

    /**
     * Set limit.
     *
     * @param int $limit The limit of rows to return
     *
     * @return static
     */
    public function limit(int $limit): self
    {
        $this->queryBuilder->limit($limit);

        return $this;
    }

    /**
     * Set offset.
     *
     * @param int $offset The offset
     *
     * @return static
     */
    public function offset(int $offset): self
    {
        $this->queryBuilder->offset($offset);

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
        $this->prepareResults();

        $results = $this->query->getResult();

        if ($this->getRelations && is_array($results)) {
            $this->setRelationshipsOnEntities($this->relationships, ...$results);
        }

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
        $results = $this->getOneOrNull();

        if (null === $results) {
            throw new EntityNotFoundException('Entity Not Found');
        }

        return $results;
    }

    /**
     * Get count results.
     *
     * @return int
     */
    public function getCount(): int
    {
        $this->prepareResults();

        return (int) $this->query->getResult();
    }

    /**
     * Set query builder and query.
     *
     * @param string        $entity  The entity
     * @param string[]|null $columns [optional] The columns
     *
     * @return void
     */
    protected function setQueryProperties(string $entity, array $columns = null): void
    {
        Cls::validateInherits($entity, Entity::class);

        $this->queryBuilder = $this->adapter->createQueryBuilder($entity)->select($columns);
        $this->query        = $this->adapter->createQuery(null, $entity);
    }

    /**
     * Validate an id.
     *
     * @param mixed $id The id
     *
     * @return void
     */
    protected function validateId($id): void
    {
        if (! is_string($id) && ! is_int($id)) {
            throw new InvalidArgumentException('ID should be an int or string only.');
        }
    }

    /**
     * Set a value to bind later.
     *
     * @param string $column The column to bind
     * @param mixed  $value  [optional] The value to bind
     *
     * @return void
     */
    protected function setValue(string $column, $value): void
    {
        $this->values[$column] = $value;
    }

    /**
     * Prepare results.
     *
     * @return void
     */
    protected function prepareResults(): void
    {
        $this->adapter->ensureTransaction();
        $this->query->prepare($this->queryBuilder->getQueryString());
        $this->bindValues();
        $this->query->execute();
    }

    /**
     * Bind values to the query.
     *
     * @return void
     */
    protected function bindValues(): void
    {
        foreach ($this->values as $column => $value) {
            $this->query->bindValue($column, $value);
        }
    }

    /**
     * Set relationships on the entities from results.
     *
     * @param array|null $relationships [optional] The relationships to get (null will get all relationships)
     * @param Entity     ...$entities   The entities to add relationships to
     *
     * @return void
     */
    protected function setRelationshipsOnEntities(array $relationships = null, Entity ...$entities): void
    {
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
            $entity->__setRelationship($this->adapter->getOrm(), $relationship);
        }
    }
}
