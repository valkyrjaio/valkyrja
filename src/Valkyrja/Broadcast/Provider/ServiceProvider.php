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

use Override;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Application\Env;
use Valkyrja\Broadcast\Broadcaster\Contract\Broadcaster;
use Valkyrja\Broadcast\Broadcaster\CryptPusherBroadcaster;
use Valkyrja\Broadcast\Broadcaster\LogBroadcaster;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Manager\Contract\Crypt;
use Valkyrja\Log\Logger\Contract\Logger;

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
    #[Override]
    public static function publishers(): array
    {
        return [
            Broadcaster::class            => [self::class, 'publishBroadcaster'],
            PusherBroadcaster::class      => [self::class, 'publishPusherBroadcaster'],
            CryptPusherBroadcaster::class => [self::class, 'publishCryptPusherBroadcaster'],
            Pusher::class                 => [self::class, 'publishPusher'],
            LogBroadcaster::class         => [self::class, 'publishLogBroadcaster'],
            NullBroadcaster::class        => [self::class, 'publishNullBroadcaster'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Broadcaster::class,
            PusherBroadcaster::class,
            CryptPusherBroadcaster::class,
            Pusher::class,
            LogBroadcaster::class,
            NullBroadcaster::class,
        ];
    }

    /**
     * Publish the broadcaster service.
     */
    public static function publishBroadcaster(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Broadcaster> $default */
        $default = $env::BROADCAST_DEFAULT_BROADCASTER;

        $container->setSingleton(
            Broadcaster::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the pusher broadcaster service.
     */
    public static function publishPusherBroadcaster(Container $container): void
    {
        $container->setSingleton(
            PusherBroadcaster::class,
            new PusherBroadcaster(
                $container->getSingleton(Pusher::class),
            )
        );
    }

    /**
     * Publish the crypt pusher broadcaster service.
     */
    public static function publishCryptPusherBroadcaster(Container $container): void
    {
        $container->setSingleton(
            CryptPusherBroadcaster::class,
            new CryptPusherBroadcaster(
                $container->getSingleton(Pusher::class),
                $container->getSingleton(Crypt::class),
            )
        );
    }

    /**
     * Publish the pusher service.
     *
     * @throws PusherException
     */
    public static function publishPusher(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $key */
        $key = $env::BROADCAST_PUSHER_KEY;
        /** @var non-empty-string $secret */
        $secret = $env::BROADCAST_PUSHER_SECRET;
        /** @var non-empty-string $id */
        $id = $env::BROADCAST_PUSHER_ID;
        /** @var non-empty-string $cluster */
        $cluster = $env::BROADCAST_PUSHER_CLUSTER;
        /** @var bool $useTls */
        $useTls = $env::BROADCAST_PUSHER_USE_TLS;

        $container->setSingleton(
            Pusher::class,
            new Pusher(
                $key,
                $secret,
                $id,
                [
                    'cluster'      => $cluster,
                    'useTLS'       => $useTls,
                    'curl_options' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ],
                ]
            )
        );
    }

    /**
     * Publish the log broadcaster service.
     */
    public static function publishLogBroadcaster(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Logger> $logger */
        $logger = $env::BROADCAST_LOG_LOGGER;

        $container->setSingleton(
            LogBroadcaster::class,
            new LogBroadcaster(
                $container->getSingleton($logger),
            )
        );
    }

    /**
     * Publish the null broadcaster service.
     */
    public static function publishNullBroadcaster(Container $container): void
    {
        $container->setSingleton(
            NullBroadcaster::class,
            new NullBroadcaster()
        );
    }
}
