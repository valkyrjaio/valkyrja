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
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\Driver\Driver;
use Valkyrja\Cache\Factory\ContainerFactory;
use Valkyrja\Cache\Factory\Contract\Factory;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Contract\Logger;

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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCache(Container $container): void
    {
        /** @var array{cache: \Valkyrja\Cache\Config|array<string, mixed>, ...} $config */
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
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     *
     * @param Container              $container
     * @param array{prefix?: string} $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        return new NullAdapter(
            $config['prefix'] ?? null
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
            [static::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     *
     * @param Container                               $container
     * @param array{prefix?: string, logger?: string} $config
     *
     * @return LogAdapter
     */
    public static function createLogAdapter(Container $container, array $config): LogAdapter
    {
        $logger = $container->getSingleton(Logger::class);

        return new LogAdapter(
            $logger->use($config['logger'] ?? null),
            $config['prefix'] ?? null
        );
    }

    /**
     * Publish the redis adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedisAdapter(Container $container): void
    {
        $container->setCallable(
            RedisAdapter::class,
            [static::class, 'createRedisAdapter']
        );
    }

    /**
     * Create a redis adapter.
     *
     * @param Container              $container
     * @param array{prefix?: string} $config
     *
     * @return RedisAdapter
     */
    public static function createRedisAdapter(Container $container, array $config): RedisAdapter
    {
        $predis = new Client($config);

        return new RedisAdapter(
            $predis,
            $config['prefix'] ?? ''
        );
    }
}
