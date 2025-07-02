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

namespace Valkyrja\Orm\Provider;

use RuntimeException;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Adapter\Contract\PdoAdapter;
use Valkyrja\Orm\Config;
use Valkyrja\Orm\Config\Connection;
use Valkyrja\Orm\Config\PdoConnection;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Factory\ContainerFactory;
use Valkyrja\Orm\Factory\Contract\Factory;
use Valkyrja\Orm\Middleware\EntityRouteMatchedMiddleware;
use Valkyrja\Orm\Migration\Migration;
use Valkyrja\Orm\Pdo\Pdo;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\DeleteQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\InsertQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\SelectQueryBuilder;
use Valkyrja\Orm\QueryBuilder\Contract\UpdateQueryBuilder;
use Valkyrja\Orm\Repository\Contract\CacheRepository;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Retriever\LocalCacheRetriever;
use Valkyrja\Orm\Retriever\Retriever;
use Valkyrja\Orm\Statement\Contract\Statement;
use Valkyrja\View\Factory\Contract\ResponseFactory as ViewResponseFactory;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Orm::class                          => [self::class, 'publishOrm'],
            Factory::class                      => [self::class, 'publishFactory'],
            Driver::class                       => [self::class, 'publishDriver'],
            Adapter::class                      => [self::class, 'publishAdapter'],
            PdoAdapter::class                   => [self::class, 'publishPdoAdapter'],
            Repository::class                   => [self::class, 'publishRepository'],
            CacheRepository::class              => [self::class, 'publishCacheRepository'],
            Persister::class                    => [self::class, 'publishPersister'],
            Retriever::class                    => [self::class, 'publishRetriever'],
            LocalCacheRetriever::class          => [self::class, 'publishLocalCacheRetriever'],
            Query::class                        => [self::class, 'publishQuery'],
            QueryBuilder::class                 => [self::class, 'publishQueryBuilder'],
            DeleteQueryBuilder::class           => [self::class, 'publishDeleteQueryBuilder'],
            InsertQueryBuilder::class           => [self::class, 'publishInsertQueryBuilder'],
            SelectQueryBuilder::class           => [self::class, 'publishSelectQueryBuilder'],
            UpdateQueryBuilder::class           => [self::class, 'publishUpdateQueryBuilder'],
            Statement::class                    => [self::class, 'publishStatement'],
            Pdo::class                          => [self::class, 'publishPdo'],
            Migration::class                    => [self::class, 'publishMigration'],
            EntityRouteMatchedMiddleware::class => [self::class, 'publishEntityRouteMatchedMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Orm::class,
            Factory::class,
            Driver::class,
            Adapter::class,
            PdoAdapter::class,
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
            Pdo::class,
            Migration::class,
            EntityRouteMatchedMiddleware::class,
        ];
    }

    /**
     * Publish the ORM service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishOrm(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Orm::class,
            new \Valkyrja\Orm\Orm(
                $container->getSingleton(Factory::class),
                $config
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
     * Publish the driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @template Driver of Driver
     *
     * @param class-string<Driver> $name
     *
     * @return Driver
     */
    public static function createDriver(Container $container, string $name, Adapter $adapter, Connection $config): Driver
    {
        return new $name(
            $adapter,
            $config
        );
    }

    /**
     * Publish the adapter service.
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [self::class, 'createAdapter']
        );
    }

    /**
     * Create an adapter.
     *
     * @template Adapter of Adapter
     *
     * @param class-string<Adapter> $name
     *
     * @return Adapter
     */
    public static function createAdapter(Container $container, string $name, Connection $config): Adapter
    {
        $orm = $container->getSingleton(Orm::class);

        return new $name(
            $orm,
            $config
        );
    }

    /**
     * Publish the PDO adapter service.
     */
    public static function publishPdoAdapter(Container $container): void
    {
        $container->setCallable(
            PdoAdapter::class,
            [self::class, 'createPdoAdapter']
        );
    }

    /**
     * Create a PDO adapter.
     *
     * @template PdoAdapter of PdoAdapter
     *
     * @param class-string<PdoAdapter> $name
     *
     * @return PdoAdapter
     */
    public static function createPdoAdapter(Container $container, string $name, PdoConnection $config): PdoAdapter
    {
        $orm = $container->getSingleton(Orm::class);

        $pdo = $container->get($config->pdoClass, [$config]);

        return new $name(
            $orm,
            $pdo,
            $config
        );
    }

    /**
     * Publish the PDO service.
     */
    public static function publishPdo(Container $container): void
    {
        $container->setCallable(
            Pdo::class,
            [self::class, 'createPdo']
        );
    }

    /**
     * Create a PDO.
     *
     * @param class-string<Pdo> $name
     */
    public static function createPdo(string $name, PdoConnection $config): Pdo
    {
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

    /**
     * Publish the repository service.
     */
    public static function publishRepository(Container $container): void
    {
        $container->setCallable(
            Repository::class,
            [self::class, 'createRepository']
        );
    }

    /**
     * Create a repository.
     *
     * @param class-string<Repository> $name
     * @param class-string<Entity>     $entity
     *
     * @return Repository<Entity>
     */
    public static function createRepository(Container $container, string $name, Driver $driver, string $entity): Repository
    {
        $orm = $container->getSingleton(Orm::class);

        return new $name(
            orm: $orm,
            driver: $driver,
            persister: $driver->getPersister(),
            entity: $entity
        );
    }

    /**
     * Publish the cache repository service.
     */
    public static function publishCacheRepository(Container $container): void
    {
        $container->setCallable(
            CacheRepository::class,
            [self::class, 'createCacheRepository']
        );
    }

    /**
     * @param class-string<CacheRepository> $name
     * @param class-string<Entity>          $entity
     *
     * @return CacheRepository<Entity>
     */
    public static function createCacheRepository(Container $container, string $name, Driver $driver, string $entity): CacheRepository
    {
        $orm   = $container->getSingleton(Orm::class);
        $cache = $container->getSingleton(Cache::class);

        return new $name(
            orm: $orm,
            driver: $driver,
            persister: $driver->getPersister(),
            cache: $cache,
            entity: $entity
        );
    }

    /**
     * Publish the persister service.
     */
    public static function publishPersister(Container $container): void
    {
        $container->setCallable(
            Persister::class,
            [self::class, 'createPersister']
        );
    }

    /**
     * Create a persister.
     *
     * @param class-string<Persister> $name
     *
     * @return Persister<Entity>
     */
    public static function createPersister(Container $container, string $name, Adapter $adapter): Persister
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the retriever service.
     */
    public static function publishRetriever(Container $container): void
    {
        $container->setCallable(
            Retriever::class,
            [self::class, 'createRetriever']
        );
    }

    /**
     * Create a retriever.
     *
     * @return Retriever<Entity>
     */
    public static function createRetriever(Container $container, Adapter $adapter): Retriever
    {
        return new Retriever(
            $adapter
        );
    }

    /**
     * Publish the retriever service.
     */
    public static function publishLocalCacheRetriever(Container $container): void
    {
        $container->setCallable(
            LocalCacheRetriever::class,
            [self::class, 'createLocalCacheRetriever']
        );
    }

    /**
     * Create a local cache retriever.
     *
     * @return LocalCacheRetriever<Entity>
     */
    public static function createLocalCacheRetriever(Container $container, Adapter $adapter): LocalCacheRetriever
    {
        return new LocalCacheRetriever(
            $adapter
        );
    }

    /**
     * Publish the query service.
     */
    public static function publishQuery(Container $container): void
    {
        $container->setCallable(
            Query::class,
            [self::class, 'createQuery']
        );
    }

    /**
     * Create a query.
     *
     * @param class-string<Query> $name
     */
    public static function createQuery(Container $container, string $name, Adapter $adapter): Query
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the query builder service.
     */
    public static function publishQueryBuilder(Container $container): void
    {
        $container->setCallable(
            QueryBuilder::class,
            [self::class, 'createQueryBuilder']
        );
    }

    /**
     * Create a query builder.
     *
     * @param class-string<QueryBuilder> $name
     */
    public static function createQueryBuilder(Container $container, string $name, Adapter $adapter): QueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the delete query builder service.
     */
    public static function publishDeleteQueryBuilder(Container $container): void
    {
        $container->setCallable(
            DeleteQueryBuilder::class,
            [self::class, 'createDeleteQueryBuilder']
        );
    }

    /**
     * Create a delete query builder.
     *
     * @param class-string<DeleteQueryBuilder> $name
     */
    public static function createDeleteQueryBuilder(Container $container, string $name, Adapter $adapter): DeleteQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the insert query builder service.
     */
    public static function publishInsertQueryBuilder(Container $container): void
    {
        $container->setCallable(
            InsertQueryBuilder::class,
            [self::class, 'createInsertQueryBuilder']
        );
    }

    /**
     * Create an insert query builder.
     *
     * @param class-string<InsertQueryBuilder> $name
     */
    public static function createInsertQueryBuilder(Container $container, string $name, Adapter $adapter): InsertQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the select query builder service.
     */
    public static function publishSelectQueryBuilder(Container $container): void
    {
        $container->setCallable(
            SelectQueryBuilder::class,
            [self::class, 'createSelectQueryBuilder']
        );
    }

    /**
     * @param class-string<SelectQueryBuilder> $name
     */
    public static function createSelectQueryBuilder(Container $container, string $name, Adapter $adapter): SelectQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the update query builder service.
     */
    public static function publishUpdateQueryBuilder(Container $container): void
    {
        $container->setCallable(
            UpdateQueryBuilder::class,
            [self::class, 'createUpdateQueryBuilder']
        );
    }

    /**
     * Create an update query builder.
     *
     * @param class-string<UpdateQueryBuilder> $name
     */
    public static function createUpdateQueryBuilder(Container $container, string $name, Adapter $adapter): UpdateQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the statement service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishStatement(Container $container): void
    {
        $container->setCallable(
            Statement::class,
            [self::class, 'createStatement']
        );
    }

    /**
     * Create a statement service.
     *
     * @param class-string<Statement> $name
     * @param array<string, mixed>    $data
     */
    public static function createStatement(Container $container, string $name, Adapter $adapter, array $data = []): Statement
    {
        return new $name(...$data);
    }

    /**
     * Publish the migration service.
     */
    public static function publishMigration(Container $container): void
    {
        $container->setCallable(
            Migration::class,
            [self::class, 'createMigration']
        );
    }

    /**
     * Create a migration.
     *
     * @param class-string<Migration> $name
     * @param array<string, mixed>    $data
     */
    public static function createMigration(Container $container, string $name, array $data = []): Migration
    {
        $orm = $container->getSingleton(Orm::class);

        return new $name($orm, ...$data);
    }

    /**
     * Publish the EntityRouteMatchedMiddleware service.
     */
    public static function publishEntityRouteMatchedMiddleware(Container $container): void
    {
        $container->setSingleton(
            EntityRouteMatchedMiddleware::class,
            new EntityRouteMatchedMiddleware(
                container: $container,
                orm: $container->getSingleton(Orm::class),
                responseFactory: $container->getSingleton(ViewResponseFactory::class),
            )
        );
    }
}
