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
use Valkyrja\ORM\Enums\WhereType;
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
 * @implements Contract<Entity>
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
     * @var class-string<Entity>
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
     * @param ORM                  $manager The orm manager
     * @param Driver               $driver  The driver
     * @param class-string<Entity> $entity  The entity class name
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
     * @inheritDoc
     */
    public function find(): self
    {
        $this->retriever = $this->driver->createRetriever()->find($this->entity);
        $this->resetRelationships();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function findOne(int|string $id): self
    {
        $this->retriever = $this->driver->createRetriever()->findOne($this->entity, $id);
        $this->resetRelationships();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): self
    {
        $this->retriever = $this->driver->createRetriever()->count($this->entity);
        $this->resetRelationships();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function columns(array $columns): self
    {
        $this->retriever->columns($columns);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, mixed $value = null, bool $setType = true): self
    {
        $this->retriever->where($column, $operator, $value, $setType);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function startWhereGroup(): self
    {
        $this->retriever->startWhereGroup();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function endWhereGroup(): self
    {
        $this->retriever->endWhereGroup();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereType(WhereType $type = WhereType::AND): self
    {
        $this->retriever->whereType($type);

        return $this;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function orderBy(string $column, string $direction = null): self
    {
        $this->retriever->orderBy($column, $direction);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): self
    {
        $this->retriever->limit($limit);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): self
    {
        $this->retriever->offset($offset);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withRelationships(array $relationships = null): self
    {
        $this->getRelations  = true;
        $this->relationships = $relationships;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAllRelationships(): self
    {
        $this->getRelations  = true;
        $this->relationships = $this->entity::getRelationshipProperties();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutRelationships(): self
    {
        $this->resetRelationships();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): array
    {
        $results = $this->retriever->getResult();

        $this->setRelationshipsOnEntities(...$results);

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getOneOrNull(): ?Entity
    {
        return $this->getResult()[0] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getOneOrFail(): Entity
    {
        return $this->retriever->getOneOrFail();
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return $this->retriever->getCount();
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->create($entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->save($entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->delete($entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $this->validateEntity($entity);

        $this->persister->softDelete($entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity !== null) {
            $this->validateEntity($entity);
        }

        $this->persister->clear($entity);
    }

    /**
     * @inheritDoc
     */
    public function persist(): bool
    {
        return $this->persister->persist();
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(string $alias = null): QueryBuilder
    {
        return $this->driver->createQueryBuilder($this->entity, $alias);
    }

    /**
     * @inheritDoc
     */
    public function createQuery(string $query): Query
    {
        return $this->driver->createQuery($query, $this->entity);
    }

    /**
     * @inheritDoc
     */
    public function getRetriever(): Retriever
    {
        return $this->retriever;
    }

    /**
     * @inheritDoc
     */
    public function getPersister(): Persister
    {
        return $this->persister;
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
    protected function setRelationship(Entity $entity, string $relationship): void
    {
        $methodName = 'set' . Str::toStudlyCase($relationship) . 'Relationship';

        if (method_exists($this, $methodName)) {
            $this->$methodName($entity);
        }
    }
}
