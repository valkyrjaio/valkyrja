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

namespace Valkyrja\ORM\Providers;

use RuntimeException;
use Valkyrja\Cache\Cache;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\AdapterFactory;
use Valkyrja\ORM\CacheRepository;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\DriverFactory;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\PDOAdapter;
use Valkyrja\ORM\PDOs\PDO;
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
use Valkyrja\ORM\Retrievers\LocalCacheRetriever;
use Valkyrja\ORM\Statement;
use Valkyrja\ORM\StatementFactory;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            ORM::class                 => 'publishORM',
            AdapterFactory::class      => 'publishAdapterFactory',
            DriverFactory::class       => 'publishDriverFactory',
            PersisterFactory::class    => 'publishPersisterFactory',
            QueryBuilderFactory::class => 'publishQueryBuilderFactory',
            QueryFactory::class        => 'publishQueryFactory',
            RepositoryFactory::class   => 'publishRepositoryFactory',
            RetrieverFactory::class    => 'publishRetrieverFactory',
            StatementFactory::class    => 'publishStatementFactory',
            Driver::class              => 'publishDriver',
            Adapter::class             => 'publishAdapter',
            PDOAdapter::class          => 'publishPdoAdapter',
            Repository::class          => 'publishRepository',
            CacheRepository::class     => 'publishCacheRepository',
            Persister::class           => 'publishPersister',
            Retriever::class           => 'publishRetriever',
            LocalCacheRetriever::class => 'publishLocalCacheRetriever',
            Query::class               => 'publishQuery',
            QueryBuilder::class        => 'publishQueryBuilder',
            Statement::class           => 'publishStatement',
            PDO::class                 => 'publishPDO',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            ORM::class,
            AdapterFactory::class,
            DriverFactory::class,
            PersisterFactory::class,
            QueryBuilderFactory::class,
            QueryFactory::class,
            RepositoryFactory::class,
            RetrieverFactory::class,
            StatementFactory::class,
            Driver::class,
            Adapter::class,
            PDOAdapter::class,
            Repository::class,
            CacheRepository::class,
            Persister::class,
            Retriever::class,
            Query::class,
            QueryBuilder::class,
            Statement::class,
            PDO::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the ORM service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishORM(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            ORM::class,
            new \Valkyrja\ORM\Managers\ORM(
                $container->getSingleton(AdapterFactory::class),
                $container->getSingleton(DriverFactory::class),
                $container->getSingleton(PersisterFactory::class),
                $container->getSingleton(QueryBuilderFactory::class),
                $container->getSingleton(QueryFactory::class),
                $container->getSingleton(RepositoryFactory::class),
                $container->getSingleton(RetrieverFactory::class),
                $container->getSingleton(StatementFactory::class),
                $config['orm']
            )
        );
    }

    /**
     * Publish the adapter factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapterFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            AdapterFactory::class,
            \Valkyrja\ORM\Factories\AdapterFactory::class
        );
    }

    /**
     * Publish the driver factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriverFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            DriverFactory::class,
            \Valkyrja\ORM\Factories\DriverFactory::class
        );
    }

    /**
     * Publish the persister factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPersisterFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            PersisterFactory::class,
            \Valkyrja\ORM\Factories\PersisterFactory::class
        );
    }

    /**
     * Publish the query builder factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQueryBuilderFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            QueryBuilderFactory::class,
            \Valkyrja\ORM\Factories\QueryBuilderFactory::class
        );
    }

    /**
     * Publish the query factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQueryFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            QueryFactory::class,
            \Valkyrja\ORM\Factories\QueryFactory::class
        );
    }

    /**
     * Publish the repository factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepositoryFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            RepositoryFactory::class,
            \Valkyrja\ORM\Factories\RepositoryFactory::class
        );
    }

    /**
     * Publish the retriever factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRetrieverFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            RetrieverFactory::class,
            \Valkyrja\ORM\Factories\RetrieverFactory::class
        );
    }

    /**
     * Publish the statement factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishStatementFactory(Container $container): void
    {
        self::publishFactory(
            $container,
            StatementFactory::class,
            \Valkyrja\ORM\Factories\StatementFactory::class
        );
    }

    /**
     * Publish a driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (string $name, Adapter $adapter, array $config): Driver {
                return new $name(
                    $adapter,
                    $config
                );
            }
        );
    }

    /**
     * Publish a adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $orm = $container->getSingleton(ORM::class);

        $container->setClosure(
            Adapter::class,
            static function (string $name, array $config) use ($orm) {
                return new $name(
                    $orm,
                    $config
                );
            }
        );
    }

    /**
     * Publish a PDO adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoAdapter(Container $container): void
    {
        $orm = $container->getSingleton(ORM::class);

        $container->setClosure(
            PDOAdapter::class,
            static function (string $name, array $config) use ($container, $orm) {
                $pdoConfig = $config['config'];
                $pdoClass  = $pdoConfig['pdo'] ?? \PDO::class;

                if ($container->has($pdoClass)) {
                    $pdo = $container->get($pdoClass, [$pdoConfig]);
                } else {
                    $pdo = $container->get(PDO::class, [$pdoClass, $pdoConfig]);
                }

                return new $name(
                    $orm,
                    $pdo,
                    $config
                );
            }
        );
    }

    /**
     * Publish a PDO service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPDO(Container $container): void
    {
        $container->setClosure(
            PDO::class,
            static function (string $name, array $config): PDO {
                if ($name === \PDO::class) {
                    // If we got here then that means the developer has opted to use the default PDO
                    // but has not defined a PDO in the service container. The reason for this requirement
                    // is that the Valkyrja PDO constructors take in a config array, whereas the default
                    // PDO takes in a DSN as the first param.
                    throw new RuntimeException('Default PDO service not found in container.');
                }

                return new $name(
                    $config
                );
            }
        );
    }

    /**
     * Publish a repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepository(Container $container): void
    {
        $orm = $container->getSingleton(ORM::class);

        $container->setClosure(
            Repository::class,
            static function (string $name, Driver $driver, string $entity) use ($orm): Repository {
                return new $name(
                    $orm,
                    $driver,
                    $entity
                );
            }
        );
    }

    /**
     * Publish a cache repository service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheRepository(Container $container): void
    {
        $orm   = $container->getSingleton(ORM::class);
        $cache = $container->getSingleton(Cache::class);

        $container->setClosure(
            CacheRepository::class,
            static function (string $name, Driver $driver, string $entity) use ($orm, $cache): CacheRepository {
                return new $name(
                    $orm,
                    $driver,
                    $cache,
                    $entity
                );
            }
        );
    }

    /**
     * Publish a persister service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPersister(Container $container): void
    {
        $container->setClosure(
            Persister::class,
            static function (string $name, Adapter $adapter): Persister {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a retriever service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRetriever(Container $container): void
    {
        $container->setClosure(
            Retriever::class,
            static function (string $name, Adapter $adapter): Retriever {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a query service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQuery(Container $container): void
    {
        $container->setClosure(
            Query::class,
            static function (string $name, Adapter $adapter): Query {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQueryBuilder(Container $container): void
    {
        $container->setClosure(
            QueryBuilder::class,
            static function (string $name, Adapter $adapter): QueryBuilder {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a statement service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishStatement(Container $container): void
    {
        $container->setClosure(
            Statement::class,
            static function (string $name, Adapter $adapter, array $data = []): Statement {
                return new $name(...$data);
            }
        );
    }

    /**
     * Publish a factory.
     *
     * @param Container $container The container
     * @param string    $interface The interface name
     * @param string    $name      The factory class name
     *
     * @return void
     */
    protected static function publishFactory(Container $container, string $interface, string $name): void
    {
        $container->setSingleton(
            $interface,
            new $name($container)
        );
    }
}
