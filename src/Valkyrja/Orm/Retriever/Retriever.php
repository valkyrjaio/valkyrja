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

namespace Valkyrja\Orm\Retriever;

use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Orm\Exception\EntityNotFoundException;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Retriever\Contract\Retriever as Contract;

use function assert;

/**
 * Class Retriever.
 *
 * @author   Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * @implements Contract<Entity>
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
     * The values to bind.
     *
     * @var array<string, array<string|float|int|bool|null>|string|float|int|bool|null>
     */
    protected array $values = [];

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
     * @inheritDoc
     *
     * @template EntityFind of Entity
     *
     * @param class-string<EntityFind> $entity
     *
     * @return static<EntityFind>
     */
    public function find(string $entity): self
    {
        return $this->setQueryProperties($entity);
    }

    /**
     * @inheritDoc
     *
     * @template EntityFindOne of Entity
     *
     * @param class-string<EntityFindOne> $entity
     * @param int|string                  $id
     *
     * @return static<EntityFindOne>
     */
    public function findOne(string $entity, int|string $id): self
    {
        $self = $this->setQueryProperties($entity);
        $self->limit(1);

        $self->where($entity::getIdField(), null, $id);

        return $self;
    }

    /**
     * @inheritDoc
     *
     * @template EntityCount of Entity
     *
     * @param class-string<EntityCount> $entity
     *
     * @return static<EntityCount>
     */
    public function count(string $entity): self
    {
        return $this->setQueryProperties($entity, Statement::COUNT_ALL);
    }

    /**
     * @inheritDoc
     */
    public function columns(string ...$columns): static
    {
        $this->queryBuilder = $this->queryBuilder->select(...$columns);

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
        $this->queryBuilder->where($column, $operator, $value, $setType);

        if (! ($value instanceof QueryBuilder)) {
            $this->setValue($column, $value);
        }

        return $this;
    }

    /**
     * Start a where clause in parentheses.
     *
     * @return static
     */
    public function startWhereGroup(): static
    {
        $this->queryBuilder->startWhereGroup();

        return $this;
    }

    /**
     * End a where clause in parentheses.
     *
     * @return static
     */
    public function endWhereGroup(): static
    {
        $this->queryBuilder->endWhereGroup();

        return $this;
    }

    /**
     * Add a where type.
     *
     * @param WhereType $type The type
     *
     * @return static
     */
    public function whereType(WhereType $type = WhereType::AND): static
    {
        $this->queryBuilder->whereType($type);

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
        $this->queryBuilder->join($table, $column1, $column2, $operator, $type, $isWhere);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): static
    {
        $this->queryBuilder->groupBy($column);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string|null $type = null): static
    {
        $this->queryBuilder->orderBy($column, $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): static
    {
        $this->queryBuilder->limit($limit);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): static
    {
        $this->queryBuilder->offset($offset);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): array
    {
        $this->prepareResults();

        /** @var Entity[] $results */
        $results = $this->query->getResult();

        return $results;
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
        $results = $this->getOneOrNull();

        if ($results === null) {
            throw new EntityNotFoundException('Entity Not Found');
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        $this->columns(Statement::COUNT_ALL);

        $this->prepareResults();

        return $this->query->getCount();
    }

    /**
     * @inheritDoc
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Set query builder and query.
     *
     * @template SetQueryEntity of Entity
     *
     * @param class-string<SetQueryEntity> $entity     The entity
     * @param string                       ...$columns [optional] The columns
     *
     * @return static<SetQueryEntity>
     */
    protected function setQueryProperties(string $entity, string ...$columns): static
    {
        assert(is_a($entity, Entity::class, true));

        /** @var static<SetQueryEntity> $self */
        $self = new self($this->adapter);

        $self->queryBuilder = $this->adapter->createQueryBuilder($entity)->select(...$columns);
        $self->query        = $this->adapter->createQuery(null, $entity);

        return $self;
    }

    /**
     * Set a value to bind later.
     *
     * @param string                                                       $column The column to bind
     * @param array<string|float|int|bool|null>|string|float|int|bool|null $value  [optional] The value to bind
     *
     * @return void
     */
    protected function setValue(string $column, array|string|float|int|bool|null $value): void
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
}
