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

use Override;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Notification\Factory\ContainerFactory;
use Valkyrja\Notification\Factory\Contract\FactoryContract;
use Valkyrja\Notification\Notifier\Contract\NotifierContract;
use Valkyrja\Notification\Notifier\Notifier;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            NotifierContract::class => [self::class, 'publishNotifier'],
            FactoryContract::class  => [self::class, 'publishFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            NotifierContract::class,
            FactoryContract::class,
        ];
    }

    /**
     * Publish the notifier service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishNotifier(ContainerContract $container): void
    {
        $container->setSingleton(
            NotifierContract::class,
            new Notifier(
                $container->getSingleton(FactoryContract::class),
                $container->getSingleton(BroadcasterContract::class),
                $container->getSingleton(MailerContract::class),
                $container->getSingleton(MessengerContract::class),
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishFactory(ContainerContract $container): void
    {
        $container->setSingleton(
            FactoryContract::class,
            new ContainerFactory(
                $container,
            )
        );
    }
}
