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
use Valkyrja\Cache\Adapter\LogAdapter;
use Valkyrja\Cache\Adapter\NullAdapter;
use Valkyrja\Cache\Adapter\RedisAdapter;
use Valkyrja\Cache\Config;
use Valkyrja\Cache\Config\LogConfiguration;
use Valkyrja\Cache\Config\NullConfiguration;
use Valkyrja\Cache\Config\RedisConfiguration;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\Driver\Driver;
use Valkyrja\Cache\Factory\ContainerFactory;
use Valkyrja\Cache\Factory\Contract\Factory;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;

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
            Cache::class        => [self::class, 'publishCache'],
            Factory::class      => [self::class, 'publishFactory'],
            Driver::class       => [self::class, 'publishDriver'],
            NullAdapter::class  => [self::class, 'publishNullAdapter'],
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
            NullAdapter::class,
            LogAdapter::class,
            RedisAdapter::class,
        ];
    }

    /**
     * Publish the cache service.
     */
    public static function publishCache(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Cache::class,
            new \Valkyrja\Cache\Cache(
                $container->getSingleton(Factory::class),
                $config
            )
        );
    }

    /**
     * Publish the factory service.
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
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the null adapter service.
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, NullConfiguration $config): NullAdapter
    {
        return new NullAdapter(
            ''
        );
    }

    /**
     * Publish the log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $container->setCallable(
            LogAdapter::class,
            [self::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     */
    public static function createLogAdapter(Container $container, LogConfiguration $config): LogAdapter
    {
        return new LogAdapter(
            $container->getSingleton(Logger::class),
            $config->prefix
        );
    }

    /**
     * Publish the redis adapter service.
     */
    public static function publishRedisAdapter(Container $container): void
    {
        $container->setCallable(
            RedisAdapter::class,
            [self::class, 'createRedisAdapter']
        );
    }

    /**
     * Create a redis adapter.
     */
    public static function createRedisAdapter(Container $container, RedisConfiguration $config): RedisAdapter
    {
        $predis = new Client(
            parameters: [
                'host' => $config->host,
                'port' => $config->port,
            ]
        );

        return new RedisAdapter(
            $predis,
            $config->prefix
        );
    }
}
