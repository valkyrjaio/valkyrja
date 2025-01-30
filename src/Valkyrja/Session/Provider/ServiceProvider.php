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

namespace Valkyrja\Session\Provider;

use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Session\Adapter\CacheAdapter;
use Valkyrja\Session\Adapter\Contract\Adapter;
use Valkyrja\Session\Adapter\CookieAdapter;
use Valkyrja\Session\Adapter\LogAdapter;
use Valkyrja\Session\Adapter\NullAdapter;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Session\Driver\Driver;
use Valkyrja\Session\Factory\ContainerFactory;
use Valkyrja\Session\Factory\Contract\Factory;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ConfigAsArray from NullAdapter
 *
 * @phpstan-import-type ConfigAsArray from NullAdapter
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Session::class       => [self::class, 'publishSession'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            NullAdapter::class   => [self::class, 'publishNullAdapter'],
            CacheAdapter::class  => [self::class, 'publishCacheAdapter'],
            CookieAdapter::class => [self::class, 'publishCookieAdapter'],
            LogAdapter::class    => [self::class, 'publishLogAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Session::class,
            Factory::class,
            Driver::class,
            NullAdapter::class,
            CacheAdapter::class,
            CookieAdapter::class,
            LogAdapter::class,
        ];
    }

    /**
     * Publish the session service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSession(Container $container): void
    {
        /** @var array{session: \Valkyrja\Session\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Session::class,
            new \Valkyrja\Session\Session(
                factory: $container->getSingleton(Factory::class),
                config: $config['session']
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
            new ContainerFactory(container: $container),
        );
    }

    /**
     * Publish a driver service.
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

    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            adapter: $adapter
        );
    }

    /**
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * @param array{id?: string, name?: string} $config
     */
    public static function createNullAdapter(Container $container, array $config): Adapter
    {
        return new NullAdapter(
            config: $config
        );
    }

    /**
     * Publish a cache adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheAdapter(Container $container): void
    {
        $container->setCallable(
            CacheAdapter::class,
            [static::class, 'createCacheAdapter']
        );
    }

    /**
     * @param array{cache?: string, ...} $config
     */
    public static function createCacheAdapter(Container $container, array $config): CacheAdapter
    {
        $cache = $container->getSingleton(Cache::class);

        return new CacheAdapter(
            cache: $cache->use($config['cache'] ?? null),
            config: $config
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
        $container->setCallable(
            LogAdapter::class,
            [static::class, 'createLogAdapter']
        );
    }

    /**
     * @param array{logger?: string, ...} $config
     */
    public static function createLogAdapter(Container $container, array $config): LogAdapter
    {
        $logger = $container->getSingleton(Logger::class);

        return new LogAdapter(
            logger: $logger->use($config['logger'] ?? null),
            config: $config
        );
    }

    /**
     * Publish the cookie adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCookieAdapter(Container $container): void
    {
        $container->setCallable(
            CookieAdapter::class,
            [static::class, 'createCookieAdapter']
        );
    }

    /**
     * @param ConfigAsArray $config
     */
    public static function createCookieAdapter(Container $container, array $config): CookieAdapter
    {
        $crypt   = $container->getSingleton(Crypt::class);
        $request = $container->getSingleton(ServerRequest::class);

        return new CookieAdapter(
            crypt: $crypt,
            request: $request,
            config: $config
        );
    }
}
