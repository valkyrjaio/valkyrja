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
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\ORM\PDOConnection as ConnectionContract;
use Valkyrja\ORM\Queries\Query as QueryClass;
use Valkyrja\ORM\Query;
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
     * @var PDO
     */
    protected PDO $connection;

    /**
     * PDOConnection constructor.
     *
     * @param Application $app
     * @param string      $connection
     */
    public function __construct(Application $app, string $connection)
    {
        $this->app        = $app;
        $this->connection = $this->getConnectionFromConfig($this->getConnectionConfig($connection));

        $this->beginTransaction();
    }

    /**
     * Get the store from the config.
     *
     * @param array $config
     *
     * @return PDO
     */
    protected function getConnectionFromConfig(array $config): PDO
    {
        $dsn = $config[ConfigKeyPart::DRIVER]
            . ':host=' . $config[ConfigKeyPart::HOST]
            . ';port=' . $config[ConfigKeyPart::PORT]
            . ';dbname=' . $config[ConfigKeyPart::DB]
            . ';charset=' . $config[ConfigKeyPart::CHARSET];

        return new PDO(
            $dsn,
            $config[ConfigKeyPart::USERNAME],
            $config[ConfigKeyPart::PASSWORD],
            []
        );
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
        $config = $this->app->config('database.connections.' . $name);

        if (null === $config) {
            throw new InvalidArgumentException('Invalid connection name specified: ' . $name);
        }

        return $config;
    }

    /**
     * The PDO.
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->connection;
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
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
        return $this->connection->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
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
        $statement = $this->connection->prepare($query);

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
        return $this->connection->lastInsertId();
    }

    /**
     * The query.
     *
     * @param string|null $query
     * @param string|null $entity
     *
     * @return Query
     */
    public function query(string $query = null, string $entity = null): Query
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
}
