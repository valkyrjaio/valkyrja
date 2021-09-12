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
use Valkyrja\ORM\Constants\Statement;
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
     * The values to bind.
     *
     * @var array
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
     */
    public function find(string $entity): self
    {
        $this->setQueryProperties($entity);

        return $this;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function count(string $entity): self
    {
        $this->setQueryProperties($entity, [Statement::COUNT_ALL]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function columns(array $columns): self
    {
        $this->queryBuilder = $this->queryBuilder->select($columns);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, $value = null): self
    {
        $this->queryBuilder->where($column, $operator, is_array($value) ? $value : null);
        $this->setValue($column, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhere(string $column, string $operator = null, $value = null): self
    {
        $this->queryBuilder->orWhere($column, $operator);
        $this->setValue($column, $value);

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
        $this->queryBuilder->join($table, $column1, $column2, $operator, $type, $isWhere);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): self
    {
        $this->queryBuilder->groupBy($column);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string $type = null): self
    {
        $this->queryBuilder->orderBy($column, $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): self
    {
        $this->queryBuilder->limit($limit);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): self
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

        return $this->query->getResult();
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
        $results = $this->getOneOrNull();

        if (null === $results) {
            throw new EntityNotFoundException('Entity Not Found');
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        $this->columns([Statement::COUNT_ALL]);

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
}
