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

namespace Valkyrja\Session\Providers;

use Valkyrja\Cache\Cache;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Http\Request;
use Valkyrja\Session\Adapters\CacheAdapter;
use Valkyrja\Session\Adapters\CookieAdapter;
use Valkyrja\Session\Adapters\NullAdapter;
use Valkyrja\Session\Adapters\PHPAdapter;
use Valkyrja\Session\Drivers\Driver;
use Valkyrja\Session\Session;

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
            Session::class       => 'publishSession',
            Driver::class        => 'publishDefaultDriver',
            CacheAdapter::class  => 'publishCacheAdapter',
            CookieAdapter::class => 'publishCookieAdapter',
            NullAdapter::class   => 'publishNullAdapter',
            PHPAdapter::class    => 'publishPHPAdapter',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Session::class,
            Driver::class,
            CacheAdapter::class,
            CookieAdapter::class,
            NullAdapter::class,
            PHPAdapter::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Session::class,
            new \Valkyrja\Session\Managers\Session(
                $container,
                $config['session']
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
     * Publish the cache adapter service.
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
            static function (array $config) use ($cache): CacheAdapter {
                return new CacheAdapter(
                    $cache,
                    $config
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
        $request = $container->getSingleton(Request::class);

        $container->setClosure(
            CookieAdapter::class,
            static function (array $config) use ($crypt, $request): CookieAdapter {
                return new CookieAdapter(
                    $crypt,
                    $request,
                    $config
                );
            }
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
        $container->setClosure(
            NullAdapter::class,
            static function (array $config): NullAdapter {
                return new NullAdapter(
                    $config
                );
            }
        );
    }

    /**
     * Publish the php adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPHPAdapter(Container $container): void
    {
        $container->setClosure(
            PHPAdapter::class,
            static function (array $config): PHPAdapter {
                return new PHPAdapter(
                    $config
                );
            }
        );
    }
}
