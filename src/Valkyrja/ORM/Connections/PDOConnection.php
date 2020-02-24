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
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\ORM\Connection as ConnectionContract;
use Valkyrja\ORM\Queries\PDOQuery;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\Statement;

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
     * @return Query
     */
    public function query(): Query
    {
        return new PDOQuery($this->connection);
    }
}
