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
use Valkyrja\ORM\CacheRepository;
use Valkyrja\ORM\DeleteQueryBuilder;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Factories\ContainerFactory;
use Valkyrja\ORM\Factory;
use Valkyrja\ORM\InsertQueryBuilder;
use Valkyrja\ORM\Migrations\Migration;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\PDOAdapter;
use Valkyrja\ORM\PDOs\PDO;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Retrievers\LocalCacheRetriever;
use Valkyrja\ORM\SelectQueryBuilder;
use Valkyrja\ORM\Statement;
use Valkyrja\ORM\UpdateQueryBuilder;

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
            Factory::class             => 'publishFactory',
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
            DeleteQueryBuilder::class  => 'publishDeleteQueryBuilder',
            InsertQueryBuilder::class  => 'publishInsertQueryBuilder',
            SelectQueryBuilder::class  => 'publishSelectQueryBuilder',
            UpdateQueryBuilder::class  => 'publishUpdateQueryBuilder',
            Statement::class           => 'publishStatement',
            PDO::class                 => 'publishPDO',
            Migration::class           => 'publishMigration',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            ORM::class,
            Factory::class,
            Driver::class,
            Adapter::class,
            PDOAdapter::class,
            Repository::class,
            CacheRepository::class,
            Persister::class,
            Retriever::class,
            Query::class,
            QueryBuilder::class,
            DeleteQueryBuilder::class,
            InsertQueryBuilder::class,
            SelectQueryBuilder::class,
            UpdateQueryBuilder::class,
            Statement::class,
            PDO::class,
            Migration::class,
        ];
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
                $container->getSingleton(Factory::class),
                $config['orm']
            )
        );
    }

    /**
     * Publish the factory.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container)
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
     * Publish a delete query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDeleteQueryBuilder(Container $container): void
    {
        $container->setClosure(
            DeleteQueryBuilder::class,
            static function (string $name, Adapter $adapter): DeleteQueryBuilder {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a insert query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishInsertQueryBuilder(Container $container): void
    {
        $container->setClosure(
            InsertQueryBuilder::class,
            static function (string $name, Adapter $adapter): InsertQueryBuilder {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a select query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSelectQueryBuilder(Container $container): void
    {
        $container->setClosure(
            SelectQueryBuilder::class,
            static function (string $name, Adapter $adapter): SelectQueryBuilder {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish a update query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishUpdateQueryBuilder(Container $container): void
    {
        $container->setClosure(
            UpdateQueryBuilder::class,
            static function (string $name, Adapter $adapter): UpdateQueryBuilder {
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
     * Publish a migration service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMigration(Container $container): void
    {
        $container->setClosure(
            Migration::class,
            static function (string $name, ORM $orm): Migration {
                return new $name($orm);
            }
        );
    }
}
