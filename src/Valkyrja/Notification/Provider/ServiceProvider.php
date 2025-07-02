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

namespace Valkyrja\Notification\Provider;

use Valkyrja\Broadcast\Contract\Broadcast;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Mail\Contract\Mail;
use Valkyrja\Notification\Contract\Notification;
use Valkyrja\Notification\Factory\ContainerFactory;
use Valkyrja\Notification\Factory\Contract\Factory;
use Valkyrja\Sms\Contract\Sms;

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
            Notification::class => [self::class, 'publishNotifier'],
            Factory::class      => [self::class, 'publishFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Notification::class,
            Factory::class,
        ];
    }

    /**
     * Publish the notifier service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNotifier(Container $container): void
    {
        $container->setSingleton(
            Notification::class,
            new \Valkyrja\Notification\Notification(
                $container->getSingleton(Factory::class),
                $container->getSingleton(Broadcast::class),
                $container->getSingleton(Mail::class),
                $container->getSingleton(Sms::class),
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
            new ContainerFactory(
                $container,
            )
        );
    }
}
