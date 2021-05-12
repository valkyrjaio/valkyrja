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

use PDO;
use Valkyrja\Cache\Cache;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Adapters\PDOAdapter;
use Valkyrja\ORM\Drivers\Driver;
use Valkyrja\ORM\Drivers\PDO\Driver as PDODriver;
use Valkyrja\ORM\Drivers\PDO\MySqlDriver;
use Valkyrja\ORM\Drivers\PDO\PgSqlDriver;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilders\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\CacheRepository;
use Valkyrja\ORM\Repositories\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Retrievers\CacheRetriever;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            ORM::class             => 'publishORM',
            Driver::class          => 'publishDefaultDriver',
            PDODriver::class       => 'publishPdoDriver',
            MySqlDriver::class     => 'publishPdoMySqlDriver',
            PgSqlDriver::class     => 'publishPdoPgSqlDriver',
            PDOAdapter::class      => 'publishPdoAdapter',
            Repository::class      => 'publishRepository',
            CacheRepository::class => 'publishCacheRepository',
            Persister::class       => 'publishPersister',
            Retriever::class       => 'publishRetriever',
            CacheRetriever::class  => 'publishCacheRetriever',
            Query::class           => 'publishQuery',
            QueryBuilder::class    => 'publishQueryBuilder',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            ORM::class,
            Driver::class,
            PDODriver::class,
            MySqlDriver::class,
            PgSqlDriver::class,
            PDOAdapter::class,
            Repository::class,
            CacheRepository::class,
            Persister::class,
            Retriever::class,
            Query::class,
            QueryBuilder::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
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
                $container,
                $config['orm']
            )
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDefaultDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (array $config, string $adapter) use ($container): Driver {
                return new Driver(
                    $container,
                    $adapter,
                    $config
                );
            }
        );
    }

    /**
     * Publish the PDO driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoDriver(Container $container): void
    {
        $container->setClosure(
            PDODriver::class,
            static function (array $config, string $adapter) use ($container): PDODriver {
                return new PDODriver(
                    $container,
                    $adapter,
                    $config
                );
            }
        );
    }

    /**
     * Publish the MySQL PDO driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoMySqlDriver(Container $container): void
    {
        $container->setClosure(
            MySqlDriver::class,
            static function (array $config, string $adapter) use ($container): MySqlDriver {
                return new MySqlDriver(
                    $container,
                    $adapter,
                    $config
                );
            }
        );
    }

    /**
     * Publish the MySQL PDO driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoPgSqlDriver(Container $container): void
    {
        $container->setClosure(
            PgSqlDriver::class,
            static function (array $config, string $adapter) use ($container): PgSqlDriver {
                return new PgSqlDriver(
                    $container,
                    $adapter,
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
            static function (array $config) use ($container, $orm) {
                return new PDOAdapter(
                    $container,
                    $orm,
                    new PDO(
                        $config['dsn'],
                        null,
                        null,
                        $config['options'] ?? []
                    ),
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
            static function (string $entity) use ($orm): Repository {
                return new Repository(
                    $orm,
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
            static function (string $entity) use ($orm, $cache): CacheRepository {
                return new CacheRepository(
                    $orm,
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
            static function (Adapter $adapter): Persister {
                return new \Valkyrja\ORM\Persisters\Persister(
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
            static function (Adapter $adapter): Retriever {
                return new \Valkyrja\ORM\Retrievers\Retriever(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a cache retriever service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheRetriever(Container $container): void
    {
        $cache = $container->getSingleton(Cache::class);

        $container->setClosure(
            CacheRetriever::class,
            static function (Adapter $adapter) use ($cache): CacheRetriever {
                return new CacheRetriever(
                    $adapter,
                    $cache
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
            static function (Adapter $adapter): Query {
                return new \Valkyrja\ORM\Queries\Query(
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
            static function (Adapter $adapter): QueryBuilder {
                return new SqlQueryBuilder(
                    $adapter
                );
            }
        );
    }
}
