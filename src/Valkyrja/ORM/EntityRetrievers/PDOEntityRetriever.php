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

namespace Valkyrja\ORM\EntityRetrievers;

use function count;
use InvalidArgumentException;
use function is_array;
use function is_int;
use function is_string;
use PDO;
use function strlen;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\EntityRetriever;
use Valkyrja\ORM\Enums\OrderBy;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\Support\ClassHelpers;

/**
 * Class PDOEntityRetriever
 *
 * @author Melech Mizrachi
 */
class PDOEntityRetriever implements EntityRetriever
{
    /**
     * The connection.
     *
     * @var PDO
     */
    protected PDO $connection;

    /**
     * The entity manager.
     *
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * PDOEntityRetriever constructor.
     *
     * @param EntityManager $entityManager
     * @param PDO           $connection
     */
    public function __construct(EntityManager $entityManager, PDO $connection)
    {
        $this->entityManager = $entityManager;
        $this->connection    = $connection;
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
    public function find(string $entity, $id, bool $getRelations = null): ?Entity
    {
        if (! is_string($id) && ! is_int($id)) {
            throw new InvalidArgumentException('ID should be an int or string only.');
        }

        /** @var Entity|string $entity */

        return $this->findBy(
                $entity,
                [$entity::getIdField() => $id],
                null,
                null,
                null,
                null,
                $getRelations
            )[0]
            ?? null;
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
    public function findBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): array {
        return (array) $this->select($entity, $columns, $criteria, $orderBy, $limit, $offset, $getRelations);
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
     * @return Entity
     */
    public function findOneBy(
        string $entity,
        array $criteria,
        array $orderBy = null,
        int $offset = null,
        array $columns = null,
        bool $getRelations = null
    ): Entity {
        return $this->findBy($entity, $criteria, $orderBy, 1, $offset, $columns, $getRelations)[0];
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
        bool $getRelations = null
    ): array {
        return $this->findBy($entity, [], $orderBy, null, null, $columns, $getRelations);
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
        bool $getRelations = null
    ) {
        ClassHelpers::validateClass($entity, Entity::class);

        // Get the query builders
        $queryBuilder = $this->getQueryBuilderForSelect($entity, $columns, $criteria, $orderBy, $limit, $offset);

        // Create a new query with the query builder
        $query = $this->entityManager->query($queryBuilder->getQueryString(), $entity);

        // Bind criteria
        $this->bindValuesForSelect($query, $criteria);

        // Execute the query
        $query->execute();

        // Get all the results from the query
        $result = $query->getResult();

        // If the results are an array (not a count result [int])
        if (is_array($result)) {
            // Try to get the entity relations
            $this->selectResultsRelations($result, $columns, $getRelations);
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
        $query = $this->entityManager->queryBuilder($entity)->select($columns);

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
            // If the criterion is null
            if ($criterion === null) {
                // Skip as we've already set the where to IS NULL
                continue;
            }

            // If the criterion is an array
            if (is_array($criterion)) {
                // Iterate through the criterion and bind each value individually
                foreach ($criterion as $index => $criterionItem) {
                    $query->bindValue($column . $index, $criterionItem);
                }

                continue;
            }

            // And bind each value to the column
            $query->bindValue($column, $criterion);
        }
    }

    /**
     * Get select results as an array of Entities.
     *
     * @param Entity[]   $entities
     * @param array|null $columns
     * @param bool|null  $getRelations
     *
     * @return void
     */
    protected function selectResultsRelations(array $entities, array $columns = null, bool $getRelations = null): void
    {
        // Iterate through the rows found
        foreach ($entities as $entity) {
            // If no columns were specified then we can safely get all the relations
            if (null === $columns && $getRelations === true) {
                // Add the model to the final results
                $entity->setRelations();
            }
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
