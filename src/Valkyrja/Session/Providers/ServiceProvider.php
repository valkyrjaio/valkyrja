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
use Valkyrja\Session\Session;
use Valkyrja\Session\Sessions\CacheSession;
use Valkyrja\Session\Sessions\CookieSession;

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
            Session::class => 'publishSession',
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
            Session::class,
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
            new \Valkyrja\Session\Sessions\Session(
                (array) $config['session']
            )
        );
    }

    /**
     * Publish the cache session service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheSession(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Session::class,
            new CacheSession(
                $container->getSingleton(Cache::class),
                (array) $config['session']
            )
        );
    }

    /**
     * Publish the cookie session service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCookieSession(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Session::class,
            new CookieSession(
                $container->getSingleton(Crypt::class),
                $container->getSingleton(Request::class),
                (array) $config['session']
            )
        );
    }
}
