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

namespace Valkyrja\Cache\Provider;

use Predis\Client;
use Valkyrja\Cache\Adapter\Contract\Adapter;
use Valkyrja\Cache\Adapter\Contract\LogAdapter;
use Valkyrja\Cache\Adapter\Contract\RedisAdapter;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\Driver\Contract\Driver;
use Valkyrja\Cache\Factory\ContainerFactory;
use Valkyrja\Cache\Factory\Contract\Factory;
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
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Cache::class        => [self::class, 'publishCache'],
            Factory::class      => [self::class, 'publishFactory'],
            Driver::class       => [self::class, 'publishDriver'],
            Adapter::class      => [self::class, 'publishAdapter'],
            LogAdapter::class   => [self::class, 'publishLogAdapter'],
            RedisAdapter::class => [self::class, 'publishRedisAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Cache::class,
            Factory::class,
            Driver::class,
            Adapter::class,
            LogAdapter::class,
            RedisAdapter::class,
        ];
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
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Cache::class,
            new \Valkyrja\Cache\Cache(
                $container->getSingleton(Factory::class),
                $config['cache']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            /**
             * @param class-string<Driver> $name
             */
            static function (string $name, Adapter $adapter): Driver {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setClosure(
            Adapter::class,
            /**
             * @param class-string<Adapter> $name
             */
            static function (string $name, array $config): Adapter {
                return new $name(
                    $config['prefix'] ?? null
                );
            }
        );
    }

    /**
     * Publish a log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $logger = $container->getSingleton(Logger::class);

        $container->setClosure(
            LogAdapter::class,
            /**
             * @param class-string<LogAdapter> $name
             */
            static function (string $name, array $config) use ($logger): LogAdapter {
                return new $name(
                    $logger->use($config['logger'] ?? null),
                    $config['prefix'] ?? null
                );
            }
        );
    }

    /**
     * Publish a redis adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedisAdapter(Container $container): void
    {
        $container->setClosure(
            RedisAdapter::class,
            /**
             * @param class-string<RedisAdapter> $name
             */
            static function (string $name, array $config): RedisAdapter {
                $predis = new Client($config);

                return new $name(
                    $predis,
                    $config['prefix'] ?? null
                );
            }
        );
    }
}
