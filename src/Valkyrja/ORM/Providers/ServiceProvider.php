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
     * Publish a PDO adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPdoAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setClosure(
            PDOAdapter::class,
            static function (string $connection) use ($config) {
                $connectionConfig = $config['connections'][$connection];

                return new PDOAdapter(
                    new PDO(
                        $connectionConfig['driver']
                        . ':host=' . $connectionConfig['host']
                        . ';port=' . $connectionConfig['port']
                        . ';dbname=' . $connectionConfig['db']
                        . ';charset=' . $connectionConfig['charset'],
                        $connectionConfig['username'],
                        $connectionConfig['password'],
                        $connectionConfig['options']
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
