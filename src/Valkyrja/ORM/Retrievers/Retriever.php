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
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever as RetrieverContract;
use Valkyrja\Support\ClassHelpers;

use function count;
use function is_array;
use function is_int;
use function is_string;
use function strlen;

/**
 * Class Retriever
 *
 * @author Melech Mizrachi
 */
class Retriever implements RetrieverContract
{
    /**
     * The entity manager.
     *
     * @var Connection
     */
    protected Connection $connection;

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
     * @throws InvalidArgumentException If id is not a string or int
     *
     * @return Entity|null
     */
    public function find(string $entity, $id, bool $getRelations = false): ?Entity
    {
        // Validate the id
        $this->validateId($id);

        /** @var Entity|string $entity */
        $idField = $entity::getIdField();

        /** @var string $entity */

        return $this->findAllBy(
                $entity,
                [$idField => $id],
                null,
                null,
                null,
                null,
                $getRelations
            )[0]
            ?? null;
    }

    /**
     * Find one entity by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findOneBy(
     *              Entity::class,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity|null
     */
    public function findBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): ?Entity {
        return $this->findAllBy($entity, $criteria, $orderBy, 1, $offset, $columns, $getRelations)[0] ?? null;
    }

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findBy(
     *              Entity::class,
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ]
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $orderBy
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAll(
        string $entity,
        array $orderBy = null,
        array $columns = null,
        bool $getRelations = false
    ): array {
        return $this->findAllBy($entity, [], $orderBy, null, null, $columns, $getRelations);
    }

    /**
     * Find entities by given criteria.
     * <code>
     *      $entityRetriever
     *          ->findBy(
     *              Entity::class,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return Entity[]
     */
    public function findAllBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = false
    ): array {
        return (array) $this->select($entity, $columns, $criteria, $orderBy, $limit, $offset, $getRelations);
    }

    /**
     * Count all the results of given criteria.
     * <code>
     *      $entityRetriever
     *          ->count(
     *              Entity::class,
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ]
     *          )
     * </code>.
     *
     * @param string $entity
     * @param array  $criteria
     *
     * @return int
     */
    public function count(string $entity, array $criteria): int
    {
        return (int) $this->select($entity, ['COUNT(*)'], $criteria);
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
     * Select entities by given criteria.
     * <code>
     *      $this
     *          ->select(
     *              [
     *                  'column',
     *                  'column2',
     *              ],
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @param bool|null  $getRelations
     *
     * @return Entity[]|int
     */
    protected function select(
        string $entity,
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        bool $getRelations = false
    ) {
        $this->connection->ensureTransaction();

        ClassHelpers::validateClass($entity, Entity::class);

        // Get the query builders
        $queryBuilder = $this->getQueryBuilderForSelect($entity, $columns, $criteria, $orderBy, $limit, $offset);

        // Create a new query with the query builder
        $query = $this->connection->createQuery($queryBuilder->getQueryString(), $entity);

        // Bind criteria
        $this->bindValuesForSelect($query, $criteria);

        // Execute the query
        $query->execute();

        // Get all the results from the query
        $result = $query->getResult();

        // If the results are an array (not a count result [int])
        if ($getRelations && is_array($result)) {
            // Try to get the entity relations
            $this->selectResultsRelations($columns, ...$result);
        }

        return $result;
    }

    /**
     * Build a select query statement by given criteria.
     * <code>
     *      $this->queryBuilder(
     *              [
     *                  'column',
     *                  'column2',
     *              ],
     *              [
     *                  'column'  => 'value',
     *                  'column2' => 'value2',
     *              ],
     *              [
     *                  'column'
     *                  'column2' => OrderBy::ASC,
     *                  'column3' => OrderBy::DESC,
     *              ],
     *              1,
     *              1
     *          )
     * </code>.
     *
     * @param string     $entity
     * @param array|null $columns
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return QueryBuilder
     */
    protected function getQueryBuilderForSelect(
        string $entity,
        array $columns = null,
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null
    ): QueryBuilder {
        // Create a new query
        $query = $this->connection->createQueryBuilder($entity)->select($columns);

        // If criteria has been passed
        if (null !== $criteria) {
            $this->setCriteriaInQuery($query, $criteria);
        }

        // If order by has been passed
        if (null !== $orderBy) {
            $this->setOrderByInQuery($query, $orderBy);
        }

        // If a limit is passed
        if (null !== $limit) {
            $this->setLimitInQuery($query, $limit);
        }

        // If an offset is passed
        if (null !== $offset) {
            $this->setOffsetInQuery($query, $offset);
        }

        return $query;
    }

    /**
     * Bind criteria for a select statement.
     *
     * @param Query      $query
     * @param array|null $criteria
     *
     * @return void
     */
    protected function bindValuesForSelect(Query $query, array $criteria = null): void
    {
        // Iterate through the criteria once more
        foreach ($criteria as $column => $criterion) {
            $this->bindValueForSelect($query, $column, $criterion);
        }
    }

    /**
     * Bind criteria value for a select statement.
     *
     * @param Query  $query
     * @param string $column
     * @param mixed  $criterion
     *
     * @return void
     */
    protected function bindValueForSelect(Query $query, string $column, $criterion): void
    {
        // If the criterion is null
        if ($criterion === null) {
            // Skip as we've already set the where to IS NULL
            return;
        }

        // If the criterion is an array
        if (is_array($criterion)) {
            $this->bindArrayValueForSelect($query, $column, $criterion);

            return;
        }

        // And bind each value to the column
        $query->bindValue($column, $criterion);
    }

    /**
     * Bind array value for a select statement.
     *
     * @param Query  $query
     * @param string $column
     * @param array  $criterion
     *
     * @return void
     */
    protected function bindArrayValueForSelect(Query $query, string $column, array $criterion): void
    {
        // Iterate through the criterion and bind each value individually
        foreach ($criterion as $index => $criterionItem) {
            $query->bindValue($column . $index, $criterionItem);
        }
    }

    /**
     * Get select results as an array of Entities.
     *
     * @param array|null $columns
     * @param Entity     ...$entities
     *
     * @return void
     */
    protected function selectResultsRelations(array $columns = null, Entity ...$entities): void
    {
        // Iterate through the rows found
        foreach ($entities as $entity) {
            // Get the entity relations
            $entity->setEntityRelations($columns);
        }
    }

    /**
     * Set the criteria in the query builder.
     *
     * @param QueryBuilder $query
     * @param array        $criteria
     *
     * @return void
     */
    protected function setCriteriaInQuery(QueryBuilder $query, array $criteria): void
    {
        // Iterate through each criteria and set the column = :column
        // so we can use bindColumn() in PDO later
        foreach ($criteria as $column => $criterion) {
            // If the criterion is null
            if ($criterion === null) {
                $this->setNullCriterionInQuery($query, $column);

                continue;
            }

            // If the criterion is an array
            if (is_array($criterion)) {
                $this->setArrayCriterionInQuery($query, $column, $criterion);

                continue;
            }

            // If the criterion has a percent at the start or the end
            if ($criterion[0] === '%' || $criterion[strlen($criterion) - 1] === '%') {
                $this->setLikeCriterionInQuery($query, $column);

                continue;
            }

            $this->setEqualCriterionInQuery($query, $column);
        }
    }

    /**
     * Set the order by options in a query builder.
     *
     * @param QueryBuilder $query
     * @param array        $orderBy
     *
     * @return void
     */
    protected function setOrderByInQuery(QueryBuilder $query, array $orderBy): void
    {
        // Iterate through each order by
        foreach ($orderBy as $column => $order) {
            // Switch through the order (value) set
            switch ($order) {
                // If the order is ascending
                case OrderBy::ASC:
                    // Set the column via the orderByAsc method
                    $query->orderByAsc($column);

                    break;
                // If the order is descending
                case OrderBy::DESC:
                    // Set the column via the orderByDesc method
                    $query->orderByDesc($column);

                    break;
                default:
                    // Otherwise set the order (which is the column)
                    $query->orderBy($order);

                    break;
            }
        }
    }

    /**
     * Set the limit in the query builder.
     *
     * @param QueryBuilder $query
     * @param int          $limit
     *
     * @return void
     */
    protected function setLimitInQuery(QueryBuilder $query, int $limit): void
    {
        // Set it in the query
        $query->limit($limit);
    }

    /**
     * Set the offset in the query builder.
     *
     * @param QueryBuilder $query
     * @param int          $offset
     *
     * @return void
     */
    protected function setOffsetInQuery(QueryBuilder $query, int $offset): void
    {
        // Set it in the query
        $query->offset($offset);
    }

    /**
     * Set a null criterion in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setNullCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' IS NULL');
    }

    /**
     * Set an array criterion in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     * @param array        $criterion
     *
     * @return void
     */
    protected function setArrayCriterionInQuery(QueryBuilder $query, string $column, array $criterion): void
    {
        $criterionConcat = '';
        $lastIndex       = count($criterion) - 1;

        // Iterate through the criterion and set each item individually to be bound later
        foreach ($criterion as $index => $criterionItem) {
            $criterionConcat .= $this->columnParam($column . $index);

            // If this is not the last index, add a comma
            if ($index < $lastIndex) {
                $criterionConcat .= ',';
            }
        }

        // Set the where statement as an in
        $query->where($column . ' IN (' . $criterionConcat . ')');
    }

    /**
     * Set a like where statement for a criterion/column in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setLikeCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' LIKE ' . $this->columnParam($column));
    }

    /**
     * Set an equal where statement for a criterion/column in the query builder.
     *
     * @param QueryBuilder $query
     * @param string       $column
     *
     * @return void
     */
    protected function setEqualCriterionInQuery(QueryBuilder $query, string $column): void
    {
        $query->where($column . ' = ' . $this->columnParam($column));
    }

    /**
     * Get a column param from a column name.
     *
     * @param string $column
     *
     * @return string
     */
    protected function columnParam(string $column): string
    {
        return ':' . $column;
    }
}
