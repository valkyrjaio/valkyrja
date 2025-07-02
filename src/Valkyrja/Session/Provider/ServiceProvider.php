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

use Valkyrja\Application\Env;
use Valkyrja\Cache\Contract\Cache;
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
use Valkyrja\Session\Adapter\PHPAdapter;
use Valkyrja\Session\Config;
use Valkyrja\Session\Config\CacheConfiguration;
use Valkyrja\Session\Config\CookieConfiguration;
use Valkyrja\Session\Config\LogConfiguration;
use Valkyrja\Session\Config\NullConfiguration;
use Valkyrja\Session\Config\PhpConfiguration;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Session\Driver\Driver;
use Valkyrja\Session\Factory\ContainerFactory;
use Valkyrja\Session\Factory\Contract\Factory;

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
            Session::class       => [self::class, 'publishSession'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            NullAdapter::class   => [self::class, 'publishNullAdapter'],
            CacheAdapter::class  => [self::class, 'publishCacheAdapter'],
            CookieAdapter::class => [self::class, 'publishCookieAdapter'],
            LogAdapter::class    => [self::class, 'publishLogAdapter'],
            PHPAdapter::class    => [self::class, 'publishPHPAdapter'],
            Config::class        => [self::class, 'publishConfig'],
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
            PHPAdapter::class,
            Config::class,
        ];
    }

    /**
     * Publish the Config service.
     */
    public static function publishConfig(Container $container): void
    {
        $env = $container->getSingleton(Env::class);

        $container->setSingleton(Config::class, Config::fromEnv($env::class));
    }

    /**
     * Publish the session service.
     */
    public static function publishSession(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Session::class,
            new \Valkyrja\Session\Session(
                factory: $container->getSingleton(Factory::class),
                config: $config
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
            new ContainerFactory(container: $container),
        );
    }

    /**
     * Publish a driver service.
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
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
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, NullConfiguration $config): Adapter
    {
        return new NullAdapter(
            config: $config
        );
    }

    /**
     * Publish a cache adapter service.
     */
    public static function publishCacheAdapter(Container $container): void
    {
        $container->setCallable(
            CacheAdapter::class,
            [self::class, 'createCacheAdapter']
        );
    }

    /**
     * Create a cache adapter.
     */
    public static function createCacheAdapter(Container $container, CacheConfiguration $config): CacheAdapter
    {
        $cache = $container->getSingleton(Cache::class);

        return new CacheAdapter(
            cache: $cache->use($config->cache),
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
            [self::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     */
    public static function createLogAdapter(Container $container, LogConfiguration $config): LogAdapter
    {
        return new LogAdapter(
            logger: $container->getSingleton(Logger::class),
            config: $config
        );
    }

    /**
     * Publish the cookie adapter service.
     */
    public static function publishCookieAdapter(Container $container): void
    {
        $container->setCallable(
            CookieAdapter::class,
            [self::class, 'createCookieAdapter']
        );
    }

    /**
     * Create a cookie adapter.
     */
    public static function createCookieAdapter(Container $container, CookieConfiguration $config): CookieAdapter
    {
        $crypt   = $container->getSingleton(Crypt::class);
        $request = $container->getSingleton(ServerRequest::class);

        return new CookieAdapter(
            crypt: $crypt,
            request: $request,
            config: $config
        );
    }

    /**
     * Publish a php adapter service.
     */
    public static function publishPhpAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [self::class, 'createPhpAdapter']
        );
    }

    /**
     * Create a php adapter.
     */
    public static function createPhpAdapter(Container $container, PhpConfiguration $config): Adapter
    {
        return new PHPAdapter(
            config: $config
        );
    }
}
