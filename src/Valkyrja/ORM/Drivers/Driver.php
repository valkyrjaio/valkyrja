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

namespace Valkyrja\ORM\Drivers;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\CacheRepository;
use Valkyrja\ORM\Driver as Contract;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Statement;
use Valkyrja\Support\Type\Cls;
use Valkyrja\Support\Type\Exceptions\InvalidClassProvidedException;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected static array $repositories = [];

    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default repository.
     *
     * @var string
     */
    protected string $defaultRepository;

    /**
     * Driver constructor.
     *
     * @param Container $container The container
     * @param Adapter   $adapter   The adapter
     * @param array     $config    The config
     */
    public function __construct(Container $container, Adapter $adapter, array $config)
    {
        $this->container         = $container;
        $this->adapter           = $adapter;
        $this->config            = $config;
        $this->defaultRepository = $config['repository'];
    }

    /**
     * Get a repository by entity name.
     *
     * @param string $entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    public function getRepository(string $entity): Repository
    {
        /** @var Entity $entity */
        $name     = $entity::getRepository() ?? $this->defaultRepository;
        $cacheKey = $name . $entity;

        return static::$repositories[$cacheKey]
            ?? static::$repositories[$cacheKey] = $this->__getRepository($name, $entity);
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->adapter->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->adapter->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        $this->adapter->ensureTransaction();
    }

    /**
     * Commit all items in the transaction.
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->adapter->commit();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->adapter->rollback();
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
        return $this->adapter->prepare($query);
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
        return $this->adapter->lastInsertId($table, $idField);
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
        return $this->adapter->createQuery($query, $entity);
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
        return $this->adapter->createQueryBuilder($entity, $alias);
    }

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return $this->adapter->createRetriever();
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->adapter->getPersister();
    }

    /**
     * Get a repository by name.
     *
     * @param string $name   The name
     * @param string $entity The entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    protected function __getRepository(string $name, string $entity): Repository
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Cls::inherits($name, CacheRepository::class) ? CacheRepository::class : Repository::class,
            [
                $this,
                $entity,
            ]
        );
    }
}
