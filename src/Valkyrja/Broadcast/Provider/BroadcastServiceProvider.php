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
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\CryptPusherBroadcaster;
use Valkyrja\Broadcast\Broadcaster\LogBroadcaster;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Data\BroadcastConfig;
use Valkyrja\Broadcast\Data\Contract\BroadcastConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;

use const CURL_IPRESOLVE_V4;
use const CURLOPT_IPRESOLVE;

class BroadcastServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the broadcast config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof BroadcastConfigContract) {
            $container->setSingleton(BroadcastConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            BroadcastConfigContract::class,
            new BroadcastConfig()
        );
    }

    /**
     * Publish the broadcaster service.
     */
    public static function publishBroadcaster(ContainerContract $container): void
    {
        $config = $container->getSingleton(BroadcastConfigContract::class);

        $container->setSingleton(
            BroadcasterContract::class,
            $container->getSingleton($config->defaultBroadcaster),
        );
    }

    /**
     * Publish the pusher broadcaster service.
     */
    public static function publishPusherBroadcaster(ContainerContract $container): void
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
    public static function publishCryptPusherBroadcaster(ContainerContract $container): void
    {
        $container->setSingleton(
            CryptPusherBroadcaster::class,
            new CryptPusherBroadcaster(
                $container->getSingleton(Pusher::class),
                $container->getSingleton(CryptContract::class),
            )
        );
    }

    /**
     * Publish the pusher service.
     *
     * @throws PusherException
     */
    public static function publishPusher(ContainerContract $container): void
    {
        $config = $container->getSingleton(BroadcastConfigContract::class);
        $pusher = $config->pusher;

        $container->setSingleton(
            Pusher::class,
            new Pusher(
                $pusher->key,
                $pusher->secret,
                $pusher->id,
                [
                    'cluster'      => $pusher->cluster,
                    'useTLS'       => $pusher->useTls,
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
    public static function publishLogBroadcaster(ContainerContract $container): void
    {
        $config = $container->getSingleton(BroadcastConfigContract::class);

        $container->setSingleton(
            LogBroadcaster::class,
            new LogBroadcaster(
                $container->getSingleton($config->log->logger),
            )
        );
    }

    /**
     * Publish the null broadcaster service.
     */
    public static function publishNullBroadcaster(ContainerContract $container): void
    {
        $container->setSingleton(
            NullBroadcaster::class,
            new NullBroadcaster()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            BroadcastConfigContract::class => [self::class, 'publishConfig'],
            BroadcasterContract::class     => [self::class, 'publishBroadcaster'],
            PusherBroadcaster::class       => [self::class, 'publishPusherBroadcaster'],
            CryptPusherBroadcaster::class  => [self::class, 'publishCryptPusherBroadcaster'],
            Pusher::class                  => [self::class, 'publishPusher'],
            LogBroadcaster::class          => [self::class, 'publishLogBroadcaster'],
            NullBroadcaster::class         => [self::class, 'publishNullBroadcaster'],
        ];
    }
}
