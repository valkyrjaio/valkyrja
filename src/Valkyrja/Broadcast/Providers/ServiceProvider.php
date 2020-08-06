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

use Valkyrja\Broadcast\Adapters\CacheAdapter;
use Valkyrja\Broadcast\Adapters\CryptPusherAdapter;
use Valkyrja\Broadcast\Adapters\LogAdapter;
use Valkyrja\Broadcast\Adapters\NullAdapter;
use Valkyrja\Broadcast\Adapters\PusherAdapter;
use Valkyrja\Broadcast\Broadcast;
use Valkyrja\Broadcast\Messages\Message;
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
            Broadcast::class          => 'publishBroadcaster',
            CacheAdapter::class       => 'publishCacheAdapter',
            CryptPusherAdapter::class => 'publishCryptPusherAdapter',
            LogAdapter::class         => 'publishLogAdapter',
            NullAdapter::class        => 'publishNullAdapter',
            PusherAdapter::class      => 'publishPusherAdapter',
            Message::class      => 'publishMessage',
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
            Broadcast::class,
            CacheAdapter::class,
            CryptPusherAdapter::class,
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
            Broadcast::class,
            new \Valkyrja\Broadcast\Managers\Broadcast(
                $container,
                $config['client']
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
     * Publish the crypt pusher adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCryptPusherAdapter(Container $container): void
    {
        $container->setSingleton(
            CryptPusherAdapter::class,
            new CryptPusherAdapter()
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

    /**
     * Publish the default message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMessage(Container $container): void
    {
        $container->setClosure(
            Message::class,
            static function () use ($container) {
                return new Message();
            }
        );
    }
}
