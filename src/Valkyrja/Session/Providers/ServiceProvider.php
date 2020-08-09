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
use Valkyrja\Session\Session;

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
            Session::class       => 'publishSession',
            CacheAdapter::class  => 'publishCacheAdapter',
            CookieAdapter::class => 'publishCookieAdapter',
            NullAdapter::class   => 'publishNullAdapter',
            PHPAdapter::class    => 'publishPHPAdapter',
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
            CacheAdapter::class,
            CookieAdapter::class,
            NullAdapter::class,
            PHPAdapter::class,
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
            new \Valkyrja\Session\Managers\Session(
                $container,
                $config['session']
            )
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
        $cache    = $container->getSingleton(Cache::class);
        $config   = $container->getSingleton('config');
        $sessions = $config['session']['sessions'];

        $container->setClosure(
            CacheAdapter::class,
            static function (string $session) use ($cache, $sessions): CacheAdapter {
                return new CacheAdapter(
                    $cache,
                    $sessions[$session]
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
        $crypt    = $container->getSingleton(Crypt::class);
        $request  = $container->getSingleton(Request::class);
        $config   = $container->getSingleton('config');
        $sessions = $config['session']['sessions'];

        $container->setClosure(
            CookieAdapter::class,
            static function (string $session) use ($crypt, $request, $sessions): CookieAdapter {
                return new CookieAdapter(
                    $crypt,
                    $request,
                    $sessions[$session]
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
        $config   = $container->getSingleton('config');
        $sessions = $config['session']['sessions'];

        $container->setClosure(
            NullAdapter::class,
            static function (string $session) use ($sessions): NullAdapter {
                return new NullAdapter(
                    $sessions[$session]
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
        $config   = $container->getSingleton('config');
        $sessions = $config['session']['sessions'];

        $container->setClosure(
            PHPAdapter::class,
            static function (string $session) use ($sessions): PHPAdapter {
                return new PHPAdapter(
                    $sessions[$session]
                );
            }
        );
    }
}
