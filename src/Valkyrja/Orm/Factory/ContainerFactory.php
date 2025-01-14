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

namespace Valkyrja\Orm\Factory;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Adapter\Contract\PdoAdapter;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Factory\Contract\Factory as Contract;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder;
use Valkyrja\Orm\Repository\Contract\CacheRepository;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Retriever\Contract\Retriever;
use Valkyrja\Orm\Schema\Contract\Migration;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\Type\BuiltIn\Support\Cls;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 */
class ContainerFactory implements Contract
{
    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * ContainerFactory constructor.
     *
     * @param Container $container The container service
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(string $name, array $config): Adapter
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            is_a($name, PdoAdapter::class, true) ? PdoAdapter::class : Adapter::class,
            [
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createDriver(Adapter $adapter, string $name, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Driver::class,
            [
                $adapter,
                $config,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createRepository(Driver $driver, string $name, string $entity): Repository
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            is_a($name, CacheRepository::class, true) ? CacheRepository::class : Repository::class,
            [
                $driver,
                $entity,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            QueryBuilder::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createDeleteQueryBuilder(Adapter $adapter, string $name): DeleteQueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            DeleteQueryBuilder::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createInsertQueryBuilder(Adapter $adapter, string $name): InsertQueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            InsertQueryBuilder::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createSelectQueryBuilder(Adapter $adapter, string $name): SelectQueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            SelectQueryBuilder::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createUpdateQueryBuilder(Adapter $adapter, string $name): UpdateQueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            UpdateQueryBuilder::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createQuery(Adapter $adapter, string $name): Query
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Query::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createPersister(Adapter $adapter, string $name): Persister
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Persister::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createRetriever(Adapter $adapter, string $name): Retriever
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Retriever::class,
            [$adapter]
        );
    }

    /**
     * @inheritDoc
     */
    public function createStatement(Adapter $adapter, string $name, array $data = []): Statement
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Statement::class,
            [$adapter, $data]
        );
    }

    /**
     * @inheritDoc
     */
    public function createMigration(string $name, array $data = []): Migration
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Migration::class,
            [$data]
        );
    }
}
