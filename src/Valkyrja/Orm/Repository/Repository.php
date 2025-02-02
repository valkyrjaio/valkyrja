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

namespace Valkyrja\Orm\Repository;

use InvalidArgumentException;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Entity\Contract\SoftDeleteEntity;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\Exception\InvalidEntityException;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository as Contract;
use Valkyrja\Orm\Retriever\Contract\Retriever;

use function assert;

/**
 * Class Repository.
 *
 * @author Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * @implements Contract<Entity>
 */
class Repository implements Contract
{
    /**
     * The retriever.
     *
     * @var Retriever<Entity>
     */
    protected Retriever $retriever;

    /**
     * The relationships to get with each result.
     *
     * @var string[]|null
     */
    protected array|null $relationships = null;

    /**
     * Whether to get relations.
     *
     * @var bool
     */
    protected bool $getRelations = false;

    /**
     * Repository constructor.
     *
     * @param Orm                  $orm       The orm manager
     * @param Driver               $driver    The driver
     * @param Persister<Entity>    $persister The persister
     * @param class-string<Entity> $entity    The entity class name
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected Orm $orm,
        protected Driver $driver,
        protected Persister $persister,
        protected string $entity
    ) {
        assert(is_a($entity, Entity::class, true));
    }

    /**
     * @inheritDoc
     */
    public function find(): static
    {
        $this->retriever = $this->driver->createRetriever()->find($this->entity);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function findOne(int|string $id): static
    {
        $this->retriever = $this->driver->createRetriever()->findOne($this->entity, $id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): static
    {
        $this->retriever = $this->driver->createRetriever()->count($this->entity);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function columns(string ...$columns): static
    {
        $this->retriever->columns(...$columns);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(
        string $column,
        string|null $operator = null,
        QueryBuilder|array|string|float|int|bool|null $value = null,
        bool $setType = true
    ): static {
        $this->retriever->where($column, $operator, $value, $setType);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function startWhereGroup(): static
    {
        $this->retriever->startWhereGroup();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function endWhereGroup(): static
    {
        $this->retriever->endWhereGroup();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereType(WhereType $type = WhereType::AND): static
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
        string|null $operator = null,
        string|null $type = null,
        bool|null $isWhere = null
    ): static {
        $this->retriever->join($table, $column1, $column2, $operator, $type, $isWhere);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string|null $direction = null): static
    {
        $this->retriever->orderBy($column, $direction);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): static
    {
        $this->retriever->limit($limit);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): static
    {
        $this->retriever->offset($offset);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): array
    {
        return $this->retriever->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getOneOrNull(): Entity|null
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

        if ($entity instanceof SoftDeleteEntity) {
            $this->persister->save($entity, $defer);

            return;
        }

        $this->persister->delete($entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidEntityException
     */
    public function clear(Entity|null $entity = null): void
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
    public function createQueryBuilder(string|null $alias = null): QueryBuilder
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
     * @throws InvalidEntityException
     */
    protected function validateEntity(Entity $entity): void
    {
        if (! ($entity instanceof $this->entity)) {
            throw new InvalidEntityException(
                'This repository expects entities to be instances of '
                . $this->entity
                . '. Entity instanced from '
                . $entity::class
                . ' provided instead.'
            );
        }
    }
}
