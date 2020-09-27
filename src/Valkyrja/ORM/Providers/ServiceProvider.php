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
                $pdoDriver   = $config['pdoDriver'] ?? 'mysql';
                $dbNameDsn   = ":dbname={$config['db']}";
                $host        = $config['host'] ?? null;
                $hostDsn     = $host ? ";host={$host}" : '';
                $port        = $config['port'] ?? null;
                $portDsn     = $port ? ";port={$port}" : '';
                $user        = $config['username'] ?? null;
                $userDsn     = $user ? ";user={$user}" : '';
                $password    = $config['password'] ?? null;
                $passwordDsn = $password ? ";password={$password}" : '';
                $schema      = $config['schema'] ?? null;
                $schemaDsn   = $schema ? ";schema={$schema}" : '';
                $sslmode     = $config['sslmode'] ?? null;
                $sslmodeDsn  = $sslmode ? ";sslmode={$sslmode}" : '';
                $charset     = $config['charset'] ?? 'utf8';
                $charsetDsn  = ";charset={$charset}";

                $dsn = $pdoDriver
                    . $dbNameDsn
                    . $hostDsn
                    . $portDsn
                    . $userDsn
                    . $passwordDsn
                    . $sslmodeDsn;

                if ($pdoDriver !== 'pgsql') {
                    $dsn .= $charsetDsn
                        . $schemaDsn;
                }

                $pdo = new PDO(
                    $dsn,
                    null,
                    null,
                    $config['options'] ?? []
                );

                if ($pdoDriver === 'pgsql' && $schema) {
                    $pdo->prepare("set search_path to {$schema}")->execute();
                }

                return new PDOAdapter(
                    $pdo,
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
