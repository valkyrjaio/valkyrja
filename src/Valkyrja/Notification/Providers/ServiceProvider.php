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

namespace Valkyrja\Notification\Providers;

use Valkyrja\Broadcast\Broadcast;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Mail\Mail;
use Valkyrja\Notification\Factories\ContainerFactory;
use Valkyrja\Notification\Factory;
use Valkyrja\Notification\Notifier;
use Valkyrja\SMS\SMS;

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
            Notifier::class => 'publishNotifier',
            Factory::class  => 'publishFactory',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Notifier::class,
            Factory::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Notifier::class,
            new \Valkyrja\Notification\Managers\Notifier(
                $container->getSingleton(Factory::class),
                $container->getSingleton(Broadcast::class),
                $container->getSingleton(Mail::class),
                $container->getSingleton(SMS::class),
                $config['notification']
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
