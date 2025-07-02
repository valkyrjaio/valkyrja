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
use Pusher\PusherException;
use Valkyrja\Broadcast\Adapter\Contract\Adapter;
use Valkyrja\Broadcast\Adapter\CryptPusherAdapter;
use Valkyrja\Broadcast\Adapter\LogAdapter;
use Valkyrja\Broadcast\Adapter\NullAdapter;
use Valkyrja\Broadcast\Adapter\PusherAdapter;
use Valkyrja\Broadcast\Config;
use Valkyrja\Broadcast\Config\LogConfiguration;
use Valkyrja\Broadcast\Config\MessageConfiguration;
use Valkyrja\Broadcast\Config\NullConfiguration;
use Valkyrja\Broadcast\Config\PusherConfiguration;
use Valkyrja\Broadcast\Contract\Broadcast;
use Valkyrja\Broadcast\Driver\Driver;
use Valkyrja\Broadcast\Factory\ContainerFactory;
use Valkyrja\Broadcast\Factory\Contract\Factory;
use Valkyrja\Broadcast\Message\Message;
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
final class ServiceProvider extends Provider
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
            NullAdapter::class        => [self::class, 'publishNullAdapter'],
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
            NullAdapter::class,
            LogAdapter::class,
            Pusher::class,
            PusherAdapter::class,
            Message::class,
        ];
    }

    /**
     * Publish the broadcaster service.\.
     */
    public static function publishBroadcaster(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Broadcast::class,
            new \Valkyrja\Broadcast\Broadcast(
                $container->getSingleton(Factory::class),
                $config
            )
        );
    }

    /**
     * Publish the factory service.\.
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish a driver service.\.
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create a driver.\.
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the null adapter service.\.
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, NullConfiguration $config): NullAdapter
    {
        return new NullAdapter();
    }

    /**
     * Publish the log adapter service.
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
            $container->getSingleton(Logger::class),
            $config
        );
    }

    /**
     * Publish the pusher adapter service.
     */
    public static function publishPusherAdapter(Container $container): void
    {
        $container->setCallable(
            PusherAdapter::class,
            [self::class, 'createPusherAdapter']
        );
    }

    /**
     * Create a pusher adapter.
     */
    public static function createPusherAdapter(Container $container, PusherConfiguration $config): PusherAdapter
    {
        return new PusherAdapter(
            $container->get(Pusher::class, [$config])
        );
    }

    /**
     * Publish the crypt pusher adapter service.
     */
    public static function publishCryptPusherAdapter(Container $container): void
    {
        $container->setCallable(
            CryptPusherAdapter::class,
            [self::class, 'createCryptPusherAdapter']
        );
    }

    /**
     * Create a crypt pusher adapter.
     */
    public static function createCryptPusherAdapter(Container $container, PusherConfiguration $config): CryptPusherAdapter
    {
        return new CryptPusherAdapter(
            $container->get(Pusher::class, [$config]),
            $container->getSingleton(Crypt::class),
            $config
        );
    }

    /**
     * Publish the Pusher service.
     */
    public static function publishPusher(Container $container): void
    {
        $container->setCallable(
            Pusher::class,
            [self::class, 'createPusher']
        );
    }

    /**
     * Create a pusher class.
     *
     * @throws PusherException
     */
    public static function createPusher(Container $container, PusherConfiguration $config): Pusher
    {
        return new Pusher(
            $config->key,
            $config->secret,
            $config->id,
            [
                'cluster'      => $config->cluster,
                'useTLS'       => $config->useTls,
                'curl_options' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ]
        );
    }

    /**
     * Publish the message service.
     */
    public static function publishMessage(Container $container): void
    {
        $container->setCallable(
            Message::class,
            [self::class, 'createMessage']
        );
    }

    /**
     * Create a message.
     */
    public static function createMessage(Container $container, MessageConfiguration $config): Message
    {
        return (new Message())->setChannel($config->channel);
    }
}
