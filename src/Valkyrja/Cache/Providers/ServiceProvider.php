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

namespace Valkyrja\Cache\Providers;

use Predis\Client;
use Valkyrja\Cache\Adapters\LogAdapter;
use Valkyrja\Cache\Adapters\NullAdapter;
use Valkyrja\Cache\Adapters\RedisAdapter;
use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Drivers\Driver;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Logger;

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
            Cache::class        => 'publishCache',
            Driver::class       => 'publishDefaultDriver',
            LogAdapter::class   => 'publishLogAdapter',
            NullAdapter::class  => 'publishNullAdapter',
            RedisAdapter::class => 'publishRedisAdapter',
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
            Cache::class,
            Driver::class,
            LogAdapter::class,
            NullAdapter::class,
            RedisAdapter::class,
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
     * Publish the cache service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCache(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Cache::class,
            new \Valkyrja\Cache\Managers\Cache(
                $container,
                $config['cache']
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
            static function (string $store, string $adapter) use ($container): Driver {
                return new Driver(
                    $container->get(
                        $adapter,
                        [
                            $store,
                        ]
                    )
                );
            }
        );
    }

    /**
     * Publish the log store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');
        $logger = $container->getSingleton(Logger::class);
        $stores = $config['cache']['stores'];

        $container->setClosure(
            LogAdapter::class,
            static function (string $crypt) use ($stores, $logger): LogAdapter {
                return new LogAdapter(
                    $logger,
                    $stores[$crypt]['prefix'] ?? null
                );
            }
        );
    }

    /**
     * Publish the null store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');
        $stores = $config['cache']['stores'];

        $container->setClosure(
            NullAdapter::class,
            static function (string $crypt) use ($stores): NullAdapter {
                return new NullAdapter(
                    $stores[$crypt]['prefix'] ?? null
                );
            }
        );
    }

    /**
     * Publish the redis store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedisAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');
        $stores = $config['cache']['stores'];

        $container->setClosure(
            RedisAdapter::class,
            static function (string $crypt) use ($stores): RedisAdapter {
                $config = $stores[$crypt];
                $predis = new Client($config);

                return new RedisAdapter(
                    $predis,
                    $config['prefix'] ?? null
                );
            }
        );
    }
}
