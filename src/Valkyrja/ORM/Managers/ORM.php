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

namespace Valkyrja\ORM\Managers;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM as Contract;
use Valkyrja\ORM\PDOAdapter;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\Type\Cls;
use Valkyrja\Support\Type\Exceptions\InvalidClassProvidedException;

use function get_class;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class ORM implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * The container service.
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
     * The connections.
     *
     * @var array
     */
    protected array $connections;

    /**
     * The default connection.
     *
     * @var string
     */
    protected string $defaultConnection;

    /**
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The default driver.
     *
     * @var string
     */
    protected string $defaultDriver;

    /**
     * ORM constructor.
     *
     * @param Container $container The container service
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container         = $container;
        $this->config            = $config;
        $this->connections       = $config['connections'];
        $this->defaultConnection = $config['default'];
        $this->defaultAdapter    = $config['adapter'];
        $this->defaultDriver     = $config['driver'];
    }

    /**
     * Use a connection by name.
     *
     * @param string|null $name    The connection name
     * @param string|null $adapter The adapter
     *
     * @return Driver
     */
    public function useConnection(string $name = null, string $adapter = null): Driver
    {
        // The connection to use
        $name ??= $this->defaultConnection;
        // The connection config to use
        $config = $this->connections[$name];
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->__useConnection($name, $config, $adapter);
    }

    /**
     * Get an adapter.
     *
     * @param string|null $name       [optional] The adapter name
     * @param string|null $connection [optional] The connection
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null, string $connection = null): Adapter
    {
        // The connection to use
        $connection ??= $this->defaultConnection;
        // The connection config to use
        $config = $this->connections[$connection];
        // The adapter to use
        $name ??= $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $connection;

        return self::$adapters[$cacheKey]
            ?? self::$adapters[$cacheKey] = $this->__getAdapter($name, $config);
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
        return $this->useConnection()->createQueryBuilder($entity, $alias);
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
        return $this->useConnection()->createQuery($query, $entity);
    }

    /**
     * Create a new retriever instance.
     *
     * @return Retriever
     */
    public function createRetriever(): Retriever
    {
        return $this->useConnection()->createRetriever();
    }

    /**
     * Get the persister.
     *
     * @return Persister
     */
    public function getPersister(): Persister
    {
        return $this->useConnection()->getPersister();
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
        return $this->useConnection()->getRepository($entity);
    }

    /**
     * Get a repository from an entity class.
     *
     * @param Entity $entity
     *
     * @throws InvalidClassProvidedException
     *
     * @return Repository
     */
    public function getRepositoryFromClass(Entity $entity): Repository
    {
        return $this->getRepository(get_class($entity));
    }

    /**
     * Initiate a transaction.
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->useConnection()->beginTransaction();
    }

    /**
     * In a transaction.
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->useConnection()->inTransaction();
    }

    /**
     * Ensure a transaction is in progress.
     *
     * @return void
     */
    public function ensureTransaction(): void
    {
        $this->useConnection()->ensureTransaction();
    }

    /**
     * Persist all entities.
     *
     * @return bool
     */
    public function persist(): bool
    {
        return $this->useConnection()->getPersister()->persist();
    }

    /**
     * Rollback the previous transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->useConnection()->rollback();
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
        return $this->useConnection()->lastInsertId($table, $idField);
    }

    /**
     * Find by given criteria.
     *
     * <code>
     *      $entityManager->find(Entity::class, true | false)
     * </code>
     *
     * @param string $entity
     *
     * @return Retriever
     */
    public function find(string $entity): Retriever
    {
        return $this->useConnection()->createRetriever()->find($entity);
    }

    /**
     * Find a single entity given its id.
     *
     * <code>
     *      $entityManager->findOne(Entity::class, 1, true | false)
     * </code>
     *
     * @param string     $entity
     * @param string|int $id
     *
     * @return Retriever
     */
    public function findOne(string $entity, $id): Retriever
    {
        return $this->useConnection()->createRetriever()->findOne($entity, $id);
    }

    /**
     * Count all the results of given criteria.
     *
     * <code>
     *      $entityManager->count(Entity::class)
     * </code>
     *
     * @param string $entity
     *
     * @return Retriever
     */
    public function count(string $entity): Retriever
    {
        return $this->useConnection()->createRetriever()->count($entity);
    }

    /**
     * Create a new entity.
     *
     * <code>
     *      $entityManager->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->useConnection()->getPersister()->create($entity, $defer);
    }

    /**
     * Update an existing entity.
     *
     * <code>
     *      $entityManager->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->useConnection()->getPersister()->save($entity, $defer);
    }

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $entityManager->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->useConnection()->getPersister()->delete($entity, $defer);
    }

    /**
     * Soft delete an existing entity.
     *
     * <code>
     *      $entityManager->softDelete(new SoftDeleteEntity(), true | false)
     * </code>
     *
     * @param SoftDeleteEntity $entity
     * @param bool             $defer [optional]
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $this->useConnection()->getPersister()->softDelete($entity, $defer);
    }

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $entityManager->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity [optional] The entity instance to remove.
     *
     * @return void
     */
    public function clear(Entity $entity = null): void
    {
        $this->useConnection()->getPersister()->clear($entity);
    }

    /**
     * Use a connection by name.
     *
     * @param string      $name    The name
     * @param array       $config  The connection config
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    protected function __useConnection(string $name, array $config, string $adapter = null): Driver
    {
        // The adapter config
        $adapter ??= $this->defaultAdapter;
        // The driver
        $driver = $config['driver'] ?? $this->defaultDriver;
        // Set the repository
        $config['repository'] ??= $this->config['repository'];

        return Cls::getDefaultableService(
            $this->container,
            $driver,
            Driver::class,
            [
                $this->getAdapter($adapter, $name),
                $config,
            ]
        );
    }

    /**
     * Get an adapter.
     *
     * @param string $name   The adapter name
     * @param array  $config The config
     *
     * @return Adapter
     */
    protected function __getAdapter(string $name, array $config): Adapter
    {
        // Set the query
        $config['query'] ??= $this->config['query'];
        // Set the query builder
        $config['queryBuilder'] ??= $this->config['queryBuilder'];
        // Set the persister
        $config['persister'] ??= $this->config['persister'];
        // Set the retriever
        $config['retriever'] ??= $this->config['retriever'];

        return Cls::getDefaultableService(
            $this->container,
            $name,
            Cls::inherits($name, PDOAdapter::class) ? PDOAdapter::class : Adapter::class,
            [
                $config,
            ]
        );
    }
}
