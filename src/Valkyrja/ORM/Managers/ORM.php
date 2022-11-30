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
use Valkyrja\ORM\Config\Config;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Factory;
use Valkyrja\ORM\Migration;
use Valkyrja\ORM\ORM as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\ORM\Statement;
use Valkyrja\Support\Type\Cls;

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
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(
        protected Factory $factory,
        protected Config|array $config
    ) {
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
            ??= $this->factory->createDriver(
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

        return $this->factory->createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder
    {
        return $this->factory->createQueryBuilder($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createQuery(Adapter $adapter, string $name): Query
    {
        return $this->factory->createQuery($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(Adapter $adapter, string $name): Retriever
    {
        return $this->factory->createRetriever($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createPersister(Adapter $adapter, string $name): Persister
    {
        return $this->factory->createPersister($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $entity): Repository
    {
        Cls::validateInherits($entity, Entity::class);

        /** @var Entity $entity */
        $name     = $entity::getRepository() ?? $this->defaultRepository;
        $cacheKey = $name . $entity;

        return static::$repositories[$cacheKey]
            ?? static::$repositories[$cacheKey] = $this->factory->createRepository(
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
        return $this->factory->createStatement($adapter, $name, $data);
    }

    /**
     * @inheritDoc
     */
    public function createMigration(string $name, array $data = []): Migration
    {
        return $this->factory->createMigration($name, $data);
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
    public function findOne(string $entity, int|string $id): Retriever
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
