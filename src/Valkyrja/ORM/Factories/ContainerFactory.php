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

namespace Valkyrja\ORM\Factories;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\CacheRepository;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Factory as Contract;
use Valkyrja\ORM\Migration;
use Valkyrja\ORM\PDOAdapter;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Statement;
use Valkyrja\Support\Type\Cls;

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
            Cls::inherits($name, PDOAdapter::class) ? PDOAdapter::class : Adapter::class,
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
            Cls::inherits($name, CacheRepository::class) ? CacheRepository::class : Repository::class,
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
