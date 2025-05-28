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

namespace Valkyrja\Orm;

use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Config\Connection;
use Valkyrja\Orm\Contract\Orm as Contract;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Factory\Contract\Factory;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Retriever\Contract\Retriever;
use Valkyrja\Orm\Schema\Contract\Migration;
use Valkyrja\Orm\Statement\Contract\Statement;

use function assert;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class Orm implements Contract
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
     * ORM constructor.
     */
    public function __construct(
        protected Factory $factory,
        protected Config $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function useConnection(string|null $name = null, string|null $adapter = null): Driver
    {
        // The connection to use
        $name ??= $this->config->defaultConnection;
        // The connection config to use
        /** @var Connection $config */
        $config = $this->config->connections->$name;
        // The driver
        $driver = $config->driverClass;

        assert(is_a($name, Driver::class, true));

        // The adapter to use
        $adapter ??= $config->adapterClass;

        assert(is_a($adapter, Adapter::class, true));

        // The cache key to use
        $cacheKey = $name . $driver . $adapter;

        return self::$drivers[$cacheKey]
            ??= $this->factory->createDriver($this->createAdapter($adapter, $config), $driver, $config);
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, Connection $config): Adapter
    {
        assert(is_a($name, Adapter::class, true));

        return $this->factory->createAdapter($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder
    {
        assert(is_a($name, QueryBuilder::class, true));

        return $this->factory->createQueryBuilder($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createQuery(Adapter $adapter, string $name): Query
    {
        assert(is_a($name, Query::class, true));

        return $this->factory->createQuery($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(Adapter $adapter, string $name): Retriever
    {
        assert(is_a($name, Retriever::class, true));

        return $this->factory->createRetriever($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function createPersister(Adapter $adapter, string $name): Persister
    {
        assert(is_a($name, Persister::class, true));

        return $this->factory->createPersister($adapter, $name);
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $entity): Repository
    {
        assert(is_a($entity, Entity::class, true));

        $entityClass = $entity;

        $name     = $entityClass::getRepository() ?? $this->defaultRepository;
        $cacheKey = $name . $entity;

        return static::$repositories[$cacheKey]
            ?? static::$repositories[$cacheKey] = $this->factory->createRepository(
                $this->useConnection($entityClass::getConnection()),
                $name,
                $entity
            );
    }

    /**
     * @inheritDoc
     */
    public function getRepositoryFromClass(Entity $entity): Repository
    {
        return $this->getRepository($entity::class);
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
    public function lastInsertId(string|null $table = null, string|null $idField = null): string
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
    public function clear(Entity|null $entity = null): void
    {
        if ($entity !== null) {
            $this->getRepositoryFromClass($entity)->clear($entity);

            return;
        }

        $this->useConnection()->getPersister()->clear($entity);
    }
}
