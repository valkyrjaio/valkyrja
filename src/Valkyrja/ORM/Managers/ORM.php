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

use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\AdapterFactory;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\DriverFactory;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\PersisterFactory;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilderFactory;
use Valkyrja\ORM\QueryFactory;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\RepositoryFactory;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\RetrieverFactory;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\ORM\Statement;
use Valkyrja\ORM\StatementFactory;

use function get_class;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class ORM implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * Repositories.
     *
     * @var Repository[]
     */
    protected static array $repositories = [];

    /**
     * The adapter factory.
     *
     * @var AdapterFactory
     */
    protected AdapterFactory $adapterFactory;

    /**
     * The driver factory.
     *
     * @var DriverFactory
     */
    protected DriverFactory $driverFactory;

    /**
     * The persister factory.
     *
     * @var PersisterFactory
     */
    protected PersisterFactory $persisterFactory;

    /**
     * The query builder factory.
     *
     * @var QueryBuilderFactory
     */
    protected QueryBuilderFactory $queryBuilderFactory;

    /**
     * The query factory.
     *
     * @var QueryFactory
     */
    protected QueryFactory $queryFactory;

    /**
     * The repository factory.
     *
     * @var RepositoryFactory
     */
    protected RepositoryFactory $repositoryFactory;

    /**
     * The retriever factory.
     *
     * @var RetrieverFactory
     */
    protected RetrieverFactory $retrieverFactory;

    /**
     * The statement factory.
     *
     * @var StatementFactory
     */
    protected StatementFactory $statementFactory;

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
     * The default repository.
     *
     * @var string
     */
    protected string $defaultRepository;

    /**
     * The default query.
     *
     * @var string
     */
    protected string $defaultQuery;

    /**
     * The default query builder.
     *
     * @var string
     */
    protected string $defaultQueryBuilder;

    /**
     * The default persister.
     *
     * @var string
     */
    protected string $defaultPersister;

    /**
     * The default retriever.
     *
     * @var string
     */
    protected string $defaultRetriever;

    /**
     * ORM constructor.
     *
     * @param AdapterFactory      $adapterFactory      The adapter factory
     * @param DriverFactory       $driverFactory       The driver factory
     * @param PersisterFactory    $persisterFactory    The persister factory
     * @param QueryBuilderFactory $queryBuilderFactory The query builder factory
     * @param QueryFactory        $queryFactory        The query factory
     * @param RepositoryFactory   $repositoryFactory   The repository factory
     * @param RetrieverFactory    $retrieverFactory    The retriever factory
     * @param StatementFactory    $statementFactory    The statement factory
     * @param array               $config              The config
     */
    public function __construct(
        AdapterFactory $adapterFactory,
        DriverFactory $driverFactory,
        PersisterFactory $persisterFactory,
        QueryBuilderFactory $queryBuilderFactory,
        QueryFactory $queryFactory,
        RepositoryFactory $repositoryFactory,
        RetrieverFactory $retrieverFactory,
        StatementFactory $statementFactory,
        array $config
    ) {
        $this->adapterFactory      = $adapterFactory;
        $this->driverFactory       = $driverFactory;
        $this->persisterFactory    = $persisterFactory;
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->queryFactory        = $queryFactory;
        $this->repositoryFactory   = $repositoryFactory;
        $this->retrieverFactory    = $retrieverFactory;
        $this->statementFactory    = $statementFactory;
        $this->config              = $config;
        $this->connections         = $config['connections'];
        $this->defaultConnection   = $config['default'];
        $this->defaultAdapter      = $config['adapter'];
        $this->defaultDriver       = $config['driver'];
        $this->defaultRepository   = $config['repository'];
        $this->defaultQuery        = $config['query'];
        $this->defaultQueryBuilder = $config['queryBuilder'];
        $this->defaultPersister    = $config['persister'];
        $this->defaultRetriever    = $config['retriever'];
    }

    /**
     * @inheritDoc
     */
    public function useConnection(string $name = null, string $adapter = null): Driver
    {
        // The connection to use
        $name ??= $this->defaultConnection;
        // The connection config to use
        $config = $this->connections[$name];
        // The driver
        $driver = $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $driver . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->driverFactory->createDriver(
                $this->createAdapter($adapter, $config),
                $driver,
                $config
            );
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        // Set the query
        $config['query'] ??= $this->defaultQuery;
        // Set the query builder
        $config['queryBuilder'] ??= $this->defaultQueryBuilder;
        // Set the persister
        $config['persister'] ??= $this->defaultPersister;
        // Set the retriever
        $config['retriever'] ??= $this->defaultRetriever;

        return $this->adapterFactory->createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder
    {
        return $this->queryBuilderFactory->createQueryBuilder($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createQuery(Adapter $adapter, string $name): Query
    {
        return $this->queryFactory->createQuery($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(Adapter $adapter, string $name): Retriever
    {
        return $this->retrieverFactory->createRetriever($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createPersister(Adapter $adapter, string $name): Persister
    {
        return $this->persisterFactory->createPersister($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $entity): Repository
    {
        /** @var Entity $entity */
        $name     = $entity::getRepository() ?? $this->defaultRepository;
        $cacheKey = $name . $entity;

        return static::$repositories[$cacheKey]
            ?? static::$repositories[$cacheKey] = $this->repositoryFactory->createRepository(
                $this->useConnection($entity::getConnection()),
                $name,
                $entity
            );
    }

    /**
     * @inheritDoc
     */
    public function getRepositoryFromClass(Entity $entity): Repository
    {
        return $this->getRepository(get_class($entity));
    }

    /**
     * @inheritDoc
     */
    public function createStatement(Adapter $adapter, string $name, array $data = []): Statement
    {
        return $this->statementFactory->createStatement($adapter, $name, $data);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return $this->useConnection()->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool
    {
        return $this->useConnection()->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function ensureTransaction(): void
    {
        $this->useConnection()->ensureTransaction();
    }

    /**
     * @inheritDoc
     */
    public function persist(): bool
    {
        return $this->useConnection()->getPersister()->persist();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return $this->useConnection()->rollback();
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(string $table = null, string $idField = null): string
    {
        return $this->useConnection()->lastInsertId($table, $idField);
    }

    /**
     * @inheritDoc
     */
    public function find(string $entity): Retriever
    {
        return $this->useConnection()->createRetriever()->find($entity);
    }

    /**
     * @inheritDoc
     */
    public function findOne(string $entity, $id): Retriever
    {
        return $this->useConnection()->createRetriever()->findOne($entity, $id);
    }

    /**
     * @inheritDoc
     */
    public function count(string $entity): Retriever
    {
        return $this->useConnection()->createRetriever()->count($entity);
    }

    /**
     * @inheritDoc
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        $this->getRepositoryFromClass($entity)->create($entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        $this->getRepositoryFromClass($entity)->save($entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        $this->getRepositoryFromClass($entity)->delete($entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        $this->getRepositoryFromClass($entity)->softDelete($entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function clear(Entity $entity = null): void
    {
        if ($entity !== null) {
            $this->getRepositoryFromClass($entity)->clear($entity);

            return;
        }

        $this->useConnection()->getPersister()->clear($entity);
    }
}
