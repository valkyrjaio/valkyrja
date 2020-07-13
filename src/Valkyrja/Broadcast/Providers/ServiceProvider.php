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

namespace Valkyrja\Broadcast\Providers;

use Valkyrja\Broadcast\Broadcaster;
use Valkyrja\Broadcast\Clients\CacheAdapter;
use Valkyrja\Broadcast\Clients\LogAdapter;
use Valkyrja\Broadcast\Clients\NullAdapter;
use Valkyrja\Broadcast\Clients\PusherAdapter;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;

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
            Broadcaster::class   => 'publishBroadcaster',
            CacheAdapter::class  => 'publishCacheAdapter',
            LogAdapter::class    => 'publishLogAdapter',
            NullAdapter::class   => 'publishNullAdapter',
            PusherAdapter::class => 'publishPusherAdapter',
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
            Broadcaster::class,
            CacheAdapter::class,
            LogAdapter::class,
            NullAdapter::class,
            PusherAdapter::class,
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
     * Publish the broadcaster service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishBroadcaster(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Broadcaster::class,
            new \Valkyrja\Broadcast\Managers\Broadcaster(
                $container,
                (array) $config['client']
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
        $container->setSingleton(
            CacheAdapter::class,
            new CacheAdapter()
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
        $container->setSingleton(
            LogAdapter::class,
            new LogAdapter()
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
        $container->setSingleton(
            NullAdapter::class,
            new NullAdapter()
        );
    }

    /**
     * Publish the pusher adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPusherAdapter(Container $container): void
    {
        $container->setSingleton(
            PusherAdapter::class,
            new PusherAdapter()
        );
    }
}
