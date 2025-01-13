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
use Valkyrja\Session\Adapter\Contract\Adapter;
use Valkyrja\Session\Adapter\Contract\CacheAdapter;
use Valkyrja\Session\Adapter\Contract\LogAdapter;
use Valkyrja\Session\Adapter\CookieAdapter;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Session\Driver\Contract\Driver;
use Valkyrja\Session\Factory\ContainerFactory;
use Valkyrja\Session\Factory\Contract\Factory;

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
            Session::class       => [self::class, 'publishSession'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            Adapter::class       => [self::class, 'publishAdapter'],
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
            Adapter::class,
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
        $container->setClosure(
            Driver::class,
            /**
             * @param class-string<Driver> $name
             */
            static function (string $name, Adapter $adapter): Driver {
                return new $name(
                    adapter: $adapter
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
                    config: $config
                );
            }
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
        $cache = $container->getSingleton(Cache::class);

        $container->setClosure(
            CacheAdapter::class,
            static function (string $name, array $config) use ($cache): CacheAdapter {
                /**
                 * @var class-string<CacheAdapter> $name
                 */
                return new $name(
                    cache: $cache,
                    config: $config
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
                    logger: $logger->use($config['logger'] ?? null),
                    config: $config
                );
            }
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
        $crypt   = $container->getSingleton(Crypt::class);
        $request = $container->getSingleton(ServerRequest::class);

        $container->setClosure(
            CookieAdapter::class,
            static function (array $config) use ($crypt, $request): CookieAdapter {
                return new CookieAdapter(
                    crypt: $crypt,
                    request: $request,
                    config: $config
                );
            }
        );
    }
}
