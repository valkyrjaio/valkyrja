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
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Mail\Mail;
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
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Notifier::class => 'publishNotifier',
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
            Notifier::class,
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
                $container,
                $container->getSingleton(Broadcast::class),
                $container->getSingleton(Mail::class),
                $container->getSingleton(SMS::class),
                $config['notification']
            )
        );
    }
}
