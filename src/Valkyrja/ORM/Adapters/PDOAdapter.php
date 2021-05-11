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

namespace Valkyrja\ORM\Adapters;

use PDO;
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter as Contract;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Statement;
use Valkyrja\ORM\Statements\PDOStatement;

use function is_bool;

/**
 * Class PDOAdapter.
 *
 * @author Melech Mizrachi
 */
class PDOAdapter implements Contract
{
    /**
     * Connections.
     *
     * @var PDO[]
     */
    protected static array $connections = [];

    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The ORM service.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * The pdo service.
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * The entity persister.
     *
     * @var Persister
     */
    protected Persister $persister;

    /**
     * The query service to use.
     *
     * @var string
     */
    protected string $queryClass = Query::class;

    /**
     * The query builder service to use.
     *
     * @var string
     */
    protected string $queryBuilderClass = QueryBuilder::class;

    /**
     * The persister service to use.
     *
     * @var string
     */
    protected string $persisterClass = Persister::class;

    /**
     * The retriever service to use.
     *
     * @var string
     */
    protected string $retrieverClass = Retriever::class;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * PDOAdapter constructor.
     *
     * @param Container $container The container
     * @param ORM       $orm       The ORM
     * @param PDO       $pdo       The PDO service
     * @param array     $config    The config
     */
    public function __construct(Container $container, ORM $orm, PDO $pdo, array $config)
    {
        $this->container = $container;
        $this->orm       = $orm;
        $this->pdo       = $pdo;
        $this->config    = $config;

        $this->queryClass        = $this->config['query'] ?? $this->queryClass;
        $this->queryBuilderClass = $this->config['queryBuilder'] ?? $this->queryBuilderClass;
        $this->persisterClass    = $this->config['persister'] ?? $this->persisterClass;
        $this->retrieverClass    = $this->config['retriever'] ?? $this->retrieverClass;

        $this->persister = $container->get($this->persisterClass, [$this]);
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        if (! $this->inTransaction()) {
            $this->beginTransaction();
        }
    }

    /**
     * Commit all items in the transaction.
     *
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Rollback the previous transaction.
     *
     * @param string $query The query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement
    {
        $statement = $this->pdo->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement preparation has failed.');
        }

        return new PDOStatement($statement);
    }

    /**
     * Get the last inserted id.
     *
     * @param string|null $table   [optional] The table last inserted into
     * @param string|null $idField [optional] The id field of the table last inserted into
     *
     * @return string
     */
    public function lastInsertId(string $table = null, string $idField = null): string
    {
        $name = null;

        if ($this->config['pdoDriver'] === 'pgsql' && $table && $idField) {
            $name = "{$table}_{$idField}_seq";
        }

        return (string) $this->pdo->lastInsertId($name);
    }

    /**
     * Create a new query instance.
     *
     * @param string|null $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function createQuery(string $query = null, string $entity = null): Query
    {
        /** @var Query $pdoQuery */
        $pdoQuery = $this->container->get($this->queryClass, [$this]);

        if (null !== $entity) {
            $pdoQuery->entity($entity);
        }

        if (null !== $query) {
            $pdoQuery->prepare($query);
        }

        return $pdoQuery;
    }

    /**
     * Create a new query builder instance.
     *
     * @param string|null $entity
     * @param string|null $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $entity = null, string $alias = null): QueryBuilder
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get($this->queryBuilderClass, [$this]);

        if (null !== $entity) {
            $queryBuilder->entity($entity, $alias);
        }

        return $queryBuilder;
    }

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return $this->container->get($this->retrieverClass, [$this]);
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->persister;
    }

    /**
     * Get the ORM.
     *
     * @return ORM
     */
    public function getOrm(): ORM
    {
        return $this->orm;
    }
}
