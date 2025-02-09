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
use Valkyrja\Broadcast\Contract\Broadcast;
use Valkyrja\Broadcast\Driver\Driver;
use Valkyrja\Broadcast\Factory\ContainerFactory;
use Valkyrja\Broadcast\Factory\Contract\Factory;
use Valkyrja\Broadcast\Message\Message;
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
     * Publish the broadcaster service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishBroadcaster(Container $container): void
    {
        /** @var array{broadcast: \Valkyrja\Broadcast\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Broadcast::class,
            new \Valkyrja\Broadcast\Broadcast(
                $container->getSingleton(Factory::class),
                $config['broadcast']
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
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
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
        $container->setCallable(
            NullAdapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        return new NullAdapter();
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
        $container->setCallable(
            LogAdapter::class,
            [static::class, 'createLogAdapter']
        );
    }

    /**
     * Create a log adapter.
     *
     * @param Container              $container
     * @param array{logger?: string} $config
     *
     * @return LogAdapter
     */
    public static function createLogAdapter(Container $container, array $config): LogAdapter
    {
        $logger = $container->getSingleton(Logger::class);

        return new LogAdapter(
            $logger->use($config['logger'] ?? null),
            $config
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
        $container->setCallable(
            PusherAdapter::class,
            [static::class, 'createPusherAdapter']
        );
    }

    /**
     * Create a pusher adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return PusherAdapter
     */
    public static function createPusherAdapter(Container $container, array $config): PusherAdapter
    {
        return new PusherAdapter(
            $container->get(Pusher::class, [$config])
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
        $container->setCallable(
            CryptPusherAdapter::class,
            [static::class, 'createCryptPusherAdapter']
        );
    }

    /**
     * Create a crypt pusher adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return CryptPusherAdapter
     */
    public static function createCryptPusherAdapter(Container $container, array $config): CryptPusherAdapter
    {
        return new CryptPusherAdapter(
            $container->get(Pusher::class, [$config]),
            $container->getSingleton(Crypt::class),
            $config
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
        $container->setCallable(
            Pusher::class,
            [static::class, 'createPusher']
        );
    }

    /**
     * Create a pusher class.
     *
     * @param array{key: string, secret: string, id: string, cluster: string, useTLS: bool} $config
     *
     * @throws PusherException
     *
     * @return Pusher
     */
    public static function createPusher(array $config): Pusher
    {
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

    /**
     * Publish the message service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMessage(Container $container): void
    {
        $container->setCallable(
            Message::class,
            [static::class, 'createMessage']
        );
    }

    /**
     * Create a message.
     *
     * @param Container              $container
     * @param array{channel: string} $config
     *
     * @return Message
     */
    public static function createMessage(Container $container, array $config): Message
    {
        return (new Message())->setChannel($config['channel']);
    }
}
