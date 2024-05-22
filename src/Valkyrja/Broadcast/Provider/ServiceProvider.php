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

namespace Valkyrja\Broadcast\Provider;

use Pusher\Pusher;
use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Adapter\Contract\LogAdapter;
use Valkyrja\Broadcast\Adapter\Contract\PusherAdapter;
use Valkyrja\Broadcast\Adapter\CryptPusherAdapter;
use Valkyrja\Broadcast\Contract\Broadcast;
use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Factory\ContainerFactory;
use Valkyrja\Broadcast\Factory\Contract\Factory;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Log\Contract\Logger;

use const CURL_IPRESOLVE_V4;
use const CURLOPT_IPRESOLVE;

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
            Broadcast::class          => [self::class, 'publishBroadcaster'],
            Factory::class            => [self::class, 'publishFactory'],
            Driver::class             => [self::class, 'publishDriver'],
            Adapter::class            => [self::class, 'publishAdapter'],
            CryptPusherAdapter::class => [self::class, 'publishCryptPusherAdapter'],
            LogAdapter::class         => [self::class, 'publishLogAdapter'],
            Pusher::class             => [self::class, 'publishPusher'],
            PusherAdapter::class      => [self::class, 'publishPusherAdapter'],
            Message::class            => [self::class, 'publishMessage'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Broadcast::class,
            Factory::class,
            CryptPusherAdapter::class,
            Driver::class,
            Adapter::class,
            LogAdapter::class,
            Pusher::class,
            PusherAdapter::class,
            Message::class,
        ];
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
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Broadcast::class,
            new \Valkyrja\Broadcast\Broadcast(
                $container->getSingleton(Factory::class),
                $config['client']
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
                    $adapter
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
                    $config
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
                    $logger->use($config['logger'] ?? null),
                    $config
                );
            }
        );
    }

    /**
     * Publish a pusher adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPusherAdapter(Container $container): void
    {
        $container->setClosure(
            PusherAdapter::class,
            /**
             * @param class-string<PusherAdapter> $name
             */
            static function (string $name, array $config) use ($container): PusherAdapter {
                return new $name(
                    $container->get(Pusher::class, [$config]),
                    $config
                );
            }
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
        $container->setClosure(
            CryptPusherAdapter::class,
            /**
             * @param class-string<CryptPusherAdapter> $name
             */
            static function (string $name, array $config) use ($container): CryptPusherAdapter {
                return new $name(
                    $container->get(Pusher::class, [$config]),
                    $container->getSingleton(Crypt::class),
                    $config
                );
            }
        );
    }

    /**
     * Publish the Pusher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPusher(Container $container): void
    {
        $container->setClosure(
            Pusher::class,
            static function (array $config): Pusher {
                return new Pusher(
                    $config['key'],
                    $config['secret'],
                    $config['id'],
                    [
                        'cluster'      => $config['cluster'],
                        'useTLS'       => $config['useTLS'],
                        'curl_options' => [
                            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                        ],
                    ]
                );
            }
        );
    }

    /**
     * Publish a message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMessage(Container $container): void
    {
        $container->setClosure(
            Message::class,
            static fn (string $name, array $config): Message => (new $name())->setChannel($config['channel'])
        );
    }
}
