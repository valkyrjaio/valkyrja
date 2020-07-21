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
use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Stores\LogStore;
use Valkyrja\Cache\Stores\NullStore;
use Valkyrja\Cache\Stores\RedisStore;
use Valkyrja\Config\Config\Config;
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
            Cache::class      => 'publishCache',
            LogStore::class   => 'publishLogStore',
            NullStore::class  => 'publishNullStore',
            RedisStore::class => 'publishRedisStore',
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
            LogStore::class,
            NullStore::class,
            RedisStore::class,
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
     * Publish the log store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogStore(Container $container): void
    {
        $config      = $container->getSingleton('config');
        $cacheConfig = $config['cache'];

        $container->setSingleton(
            LogStore::class,
            new LogStore(
                $container->getSingleton(Logger::class),
                $cacheConfig['prefix']
            )
        );
    }

    /**
     * Publish the null store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullStore(Container $container): void
    {
        $config      = $container->getSingleton('config');
        $cacheConfig = $config['cache'];

        $container->setSingleton(
            NullStore::class,
            new NullStore(
                $cacheConfig['prefix']
            )
        );
    }

    /**
     * Publish the redis store service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedisStore(Container $container): void
    {
        $config      = $container->getSingleton('config');
        $cacheConfig = $config['cache'];
        $predis      = new Client($config['connections']['redis']);

        $container->setSingleton(
            RedisStore::class,
            new RedisStore(
                $predis,
                $cacheConfig['prefix']
            )
        );
    }
}
