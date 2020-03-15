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

namespace Valkyrja\ORM\Retrievers;

use InvalidArgumentException;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever as RetrieverContract;

use Valkyrja\Support\ClassHelpers;

use function is_array;
use function is_int;
use function is_string;

/**
 * Class Retriever
 *
 * @author Melech Mizrachi
 */
class Retriever implements RetrieverContract
{
    /**
     * The columns.
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * Whether to get relations.
     *
     * @var bool
     */
    protected bool $getRelations = false;

    /**
     * Whether to only retrieve one.
     *
     * @var bool
     */
    protected bool $one = false;

    /**
     * The connection.
     *
     * @var Connection
     */
    protected Connection $connection;

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
     * Retriever constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Find by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findBy(
     *              Entity::class,
     *              1
     *          )
     * </code>.
     *
     * @param string    $entity
     * @param bool|null $getRelations
     *
     * @return static
     */
    public function find(string $entity, bool $getRelations = false): self
    {
        ClassHelpers::validateClass($entity, Entity::class);

        $this->queryBuilder = $this->connection->createQueryBuilder($entity)->select();
        $this->query        = $this->connection->createQuery('', $entity);
        $this->getRelations = $getRelations;

        return $this;
    }

    /**
     * Find a single entity given its id.
     * <code>
     *      $entityRetriever
     *          ->find(
     *              Entity::class,
     *              1,
     *              true | false | null
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param string|int $id
     * @param bool|null  $getRelations
     *
     * @return static
     */
    public function findOne(string $entity, $id, bool $getRelations = false): self
    {
        ClassHelpers::validateClass($entity, Entity::class);

        $this->validateId($id);

        $this->queryBuilder = $this->connection->createQueryBuilder($entity)->select();
        $this->query        = $this->connection->createQuery('', $entity);
        $this->one          = true;
        $this->getRelations = $getRelations;

        /** @var Entity $entity */
        $this->queryBuilder->where($entity::getIdField(), $id);
        $this->query->bindValue($entity::getIdField(), $id);

        return $this;
    }

    /**
     * Count all the results of given criteria.
     * <code>
     *      $entityRetriever
     *          ->count(
     *              Entity::class
     *          )
     * </code>.
     *
     * @param string $entity
     *
     * @return static
     */
    public function count(string $entity): self
    {
        ClassHelpers::validateClass($entity, Entity::class);

        $this->queryBuilder = $this->connection->createQueryBuilder($entity)->select(['COUNT(*)']);
        $this->query        = $this->connection->createQuery('', $entity);

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
        $this->columns      = $columns;
        $this->queryBuilder = $this->queryBuilder->select($columns);

        return $this;
    }

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string     $column
     * @param string     $operator
     * @param mixed|null $value
     *
     * @return static
     */
    public function where(string $column, string $operator, $value = null): self
    {
        $this->queryBuilder->where($column, $operator, $value);
        $this->query->bindValue($column, $value);

        return $this;
    }

    /**
     * Add an additional `OR` where condition.
     *
     * @param string     $column
     * @param string     $operator
     * @param mixed|null $value
     *
     * @return static
     */
    public function orWhere(string $column, string $operator, $value = null): self
    {
        $this->queryBuilder->orWhere($column, $operator, $value);
        $this->query->bindValue($column, $value);

        return $this;
    }

    /**
     * Set order by.
     *
     * @param string      $orderBy
     * @param string|null $type
     *
     * @return static
     */
    public function orderBY(string $orderBy, string $type = null): self
    {
        $this->queryBuilder->orderBy($orderBy, $type);

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
        $this->queryBuilder->limit($limit);

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
        $this->queryBuilder->offset($offset);

        return $this;
    }

    /**
     * Get results.
     *
     * @return Entity[]|Entity|int|null
     */
    public function getResults()
    {
        $this->connection->ensureTransaction();
        $this->query->prepare($this->queryBuilder->getQueryString())->execute();

        $results = $this->query->getResult();

        if (is_int($results)) {
            return $results;
        }

        if ($this->getRelations && is_array($results)) {
            $this->setRelations($this->columns, ...$results);
        }

        if ($this->one) {
            return $results[0] ?? null;
        }

        return $results;
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
     * Set result relations.
     *
     * @param array|null $columns
     * @param Entity     ...$entities
     *
     * @return void
     */
    protected function setRelations(array $columns = null, Entity ...$entities): void
    {
        // Iterate through the rows found
        foreach ($entities as $entity) {
            // Get the entity relations
            $entity->setEntityRelations($columns);
        }
    }
}
