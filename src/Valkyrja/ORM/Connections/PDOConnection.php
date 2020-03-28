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

namespace Valkyrja\ORM\Connections;

use InvalidArgumentException;
use PDO;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Configs\ORM\Connection;
use Valkyrja\ORM\PDOConnection as ConnectionContract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Persisters\Persister as PersisterClass;
use Valkyrja\ORM\Queries\Query as QueryClass;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilders\SqlQueryBuilder;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Retrievers\Retriever as RetrieverClass;
use Valkyrja\ORM\Statement;

use function is_bool;

/**
 * Class PDOConnection.
 *
 * @author Melech Mizrachi
 */
class PDOConnection implements ConnectionContract
{
    /**
     * Connections.
     *
     * @var PDO[]
     */
    protected static array $connections = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The connection.
     *
     * @var PDO|null
     */
    protected ?PDO $connection = null;

    /**
     * The entity persister.
     *
     * @var Persister
     */
    protected Persister $persister;

    /**
     * The config.
     *
     * @var Connection|array
     */
    protected array $config;

    /**
     * PDOConnection constructor.
     *
     * @param string $connection
     */
    public function __construct(string $connection)
    {
        $this->config    = config()['orm']['connections'][$connection];
        $this->persister = new PersisterClass($this);
    }

    /**
     * Get the PDO.
     *
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->connection ?? ($this->connection = $this->getConnectionFromConfig());
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->getPDO()->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->getPDO()->inTransaction();
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
        return $this->getPDO()->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->getPDO()->rollBack();
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
        $statement = $this->getPDO()->prepare($query);

        if (is_bool($statement)) {
            throw new RuntimeException('Statement preparation has failed.');
        }

        return new PDOStatement($statement);
    }

    /**
     * Get the last inserted id.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->getPDO()->lastInsertId();
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
        $pdoQuery = new QueryClass($this);

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
        $queryBuilder = new SqlQueryBuilder($this);

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
        return new RetrieverClass($this);
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
     * Get the store from the config.
     *
     * @return PDO
     */
    protected function getConnectionFromConfig(): PDO
    {
        $dsn = $this->config['driver']
            . ':host=' . $this->config['host']
            . ';port=' . $this->config['port']
            . ';dbname=' . $this->config['db']
            . ';charset=' . $this->config['charset'];

        return new PDO($dsn, $this->config['username'], $this->config['password'], []);
    }

    /**
     * Get the store config.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getConnectionConfig(string $name): array
    {
        $config = $this->config[$name] ?? null;

        if (null === $config) {
            throw new InvalidArgumentException('Invalid connection name specified: ' . $name);
        }

        return $config;
    }
}
