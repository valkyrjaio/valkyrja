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
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Adapter\Contract\PdoAdapter;
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
class ServiceProvider extends Provider
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
        /** @var array{orm: \Valkyrja\Orm\Config\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Orm::class,
            new \Valkyrja\Orm\Orm(
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
            [static::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @param class-string<Driver> $name
     * @param Adapter              $adapter
     * @param array<string, mixed> $config
     *
     * @return Driver
     */
    public static function createDriver(string $name, Adapter $adapter, array $config): Driver
    {
        return new $name(
            $adapter,
            $config
        );
    }

    /**
     * Publish the adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [static::class, 'createAdapter']
        );
    }

    /**
     * Create an adapter.
     *
     * @param Container             $container
     * @param class-string<Adapter> $name
     * @param array<string, mixed>  $config
     *
     * @return Adapter
     */
    public static function createAdapter(Container $container, string $name, array $config): Adapter
    {
        $orm = $container->getSingleton(Orm::class);

        return new $name(
            $orm,
            $config
        );
    }

    /**
     * Publish the PDO adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoAdapter(Container $container): void
    {
        $container->setCallable(
            PdoAdapter::class,
            [static::class, 'createPdoAdapter']
        );
    }

    /**
     * Create a PDO adapter.
     *
     * @param Container                $container
     * @param class-string<PdoAdapter> $name
     * @param array{config: array{pdo?: string, ...}, ...} $config
     *
     * @return PdoAdapter
     */
    public static function createPdoAdapter(Container $container, string $name, array $config): PdoAdapter
    {
        $orm = $container->getSingleton(Orm::class);

        $pdoConfig = $config['config'];
        $pdoClass  = $pdoConfig['pdo'] ?? \PDO::class;

        if ($container->has($pdoClass)) {
            $pdo = $container->get($pdoClass, [$pdoConfig]);
        } else {
            $pdo = $container->get(Pdo::class, [$pdoClass, $pdoConfig]);
        }

        return new $name(
            $orm,
            $pdo,
            $config
        );
    }

    /**
     * Publish the PDO service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdo(Container $container): void
    {
        $container->setCallable(
            Pdo::class,
            [static::class, 'createPdo']
        );
    }

    /**
     * Create a PDO.
     *
     * @param class-string<Pdo>    $name
     * @param array<string, mixed> $config
     *
     * @return Pdo
     */
    public static function createPdo(string $name, array $config): Pdo
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRepository(Container $container): void
    {
        $container->setCallable(
            Repository::class,
            [static::class, 'createRepository']
        );
    }

    /**
     * Create a repository.
     *
     * @param Container                $container
     * @param class-string<Repository> $name
     * @param Driver                   $driver
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheRepository(Container $container): void
    {
        $container->setCallable(
            CacheRepository::class,
            [static::class, 'createCacheRepository']
        );
    }

    /**
     * @param Container                     $container
     * @param class-string<CacheRepository> $name
     * @param Driver                        $driver
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPersister(Container $container): void
    {
        $container->setCallable(
            Persister::class,
            [static::class, 'createPersister']
        );
    }

    /**
     * Create a persister.
     *
     * @param class-string<Persister> $name
     * @param Adapter                 $adapter
     *
     * @return Persister<Entity>
     */
    public static function createPersister(string $name, Adapter $adapter): Persister
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the retriever service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRetriever(Container $container): void
    {
        $container->setCallable(
            Retriever::class,
            [static::class, 'createRetriever']
        );
    }

    /**
     * Create a retriever.
     *
     * @param Container $container
     * @param Adapter   $adapter
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLocalCacheRetriever(Container $container): void
    {
        $container->setCallable(
            LocalCacheRetriever::class,
            [static::class, 'createLocalCacheRetriever']
        );
    }

    /**
     * Create a local cache retriever.
     *
     * @param Container $container
     * @param Adapter   $adapter
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQuery(Container $container): void
    {
        $container->setCallable(
            Query::class,
            [static::class, 'createQuery']
        );
    }

    /**
     * Create a query.
     *
     * @param class-string<Query> $name
     * @param Adapter             $adapter
     *
     * @return Query
     */
    public static function createQuery(string $name, Adapter $adapter): Query
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishQueryBuilder(Container $container): void
    {
        $container->setCallable(
            QueryBuilder::class,
            [static::class, 'createQueryBuilder']
        );
    }

    /**
     * Create a query builder.
     *
     * @param class-string<QueryBuilder> $name
     * @param Adapter                    $adapter
     *
     * @return QueryBuilder
     */
    public static function createQueryBuilder(string $name, Adapter $adapter): QueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the delete query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDeleteQueryBuilder(Container $container): void
    {
        $container->setCallable(
            DeleteQueryBuilder::class,
            [static::class, 'createDeleteQueryBuilder']
        );
    }

    /**
     * Create a delete query builder.
     *
     * @param class-string<DeleteQueryBuilder> $name
     * @param Adapter                          $adapter
     *
     * @return DeleteQueryBuilder
     */
    public static function createDeleteQueryBuilder(string $name, Adapter $adapter): DeleteQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the insert query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishInsertQueryBuilder(Container $container): void
    {
        $container->setCallable(
            InsertQueryBuilder::class,
            [static::class, 'createInsertQueryBuilder']
        );
    }

    /**
     * Create an insert query builder.
     *
     * @param class-string<InsertQueryBuilder> $name
     * @param Adapter                          $adapter
     *
     * @return InsertQueryBuilder
     */
    public static function createInsertQueryBuilder(string $name, Adapter $adapter): InsertQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the select query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSelectQueryBuilder(Container $container): void
    {
        $container->setCallable(
            SelectQueryBuilder::class,
            [static::class, 'createSelectQueryBuilder']
        );
    }

    /**
     * @param class-string<SelectQueryBuilder> $name
     */
    public static function createSelectQueryBuilder(string $name, Adapter $adapter): SelectQueryBuilder
    {
        return new $name(
            $adapter
        );
    }

    /**
     * Publish the update query builder service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishUpdateQueryBuilder(Container $container): void
    {
        $container->setCallable(
            UpdateQueryBuilder::class,
            [static::class, 'createUpdateQueryBuilder']
        );
    }

    /**
     * Create an update query builder.
     *
     * @param class-string<UpdateQueryBuilder> $name
     * @param Adapter                          $adapter
     *
     * @return UpdateQueryBuilder
     */
    public static function createUpdateQueryBuilder(string $name, Adapter $adapter): UpdateQueryBuilder
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
            [static::class, 'createStatement']
        );
    }

    /**
     * Create a statement service.
     *
     * @param class-string<Statement> $name
     * @param Adapter                 $adapter
     * @param array<string, mixed>    $data
     *
     * @return Statement
     */
    public static function createStatement(string $name, Adapter $adapter, array $data = []): Statement
    {
        return new $name(...$data);
    }

    /**
     * Publish the migration service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMigration(Container $container): void
    {
        $container->setCallable(
            Migration::class,
            [static::class, 'createMigration']
        );
    }

    /**
     * Create a migration.
     *
     * @param Container               $container
     * @param class-string<Migration> $name
     * @param array<string, mixed>    $data
     *
     * @return Migration
     */
    public static function createMigration(Container $container, string $name, array $data = []): Migration
    {
        $orm = $container->getSingleton(Orm::class);

        return new $name($orm, ...$data);
    }

    /**
     * Publish the EntityRouteMatchedMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
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
