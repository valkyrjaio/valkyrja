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
use Valkyrja\ORM\Adapters\PDOAdapter;
use Valkyrja\ORM\Drivers\Driver;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Repositories\CacheRepository;
use Valkyrja\ORM\Repositories\Repository;

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
            PDOAdapter::class      => 'publishPdoAdapter',
            Repository::class      => 'publishRepository',
            CacheRepository::class => 'publishCacheRepository',
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
            PDOAdapter::class,
            Repository::class,
            CacheRepository::class,
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
                    $container->get(
                        $adapter,
                        [
                            $config,
                        ]
                    )
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
        $container->setClosure(
            PDOAdapter::class,
            static function (array $config) {
                $schema = $config['schema'];
                $schemaDsn = $schema ? ';schema=' . $schema : '';
                $charset = $config['charset'] ?? 'utf8';

                return new PDOAdapter(
                    new PDO(
                        $config['pdoDriver']
                        . ':host=' . $config['host']
                        . ';port=' . $config['port']
                        . ';dbname=' . $config['db']
                        . ';charset=' . $charset
                        . $schemaDsn,
                        $config['username'],
                        $config['password'],
                        $config['options'] ?? []
                    )
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
            static function (string $entity) use ($orm) {
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
            static function (string $entity) use ($orm, $cache) {
                return new CacheRepository(
                    $orm,
                    $cache,
                    $entity
                );
            }
        );
    }
}
