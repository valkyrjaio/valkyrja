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
use Valkyrja\Broadcast\Broadcaster\Contract\Broadcaster;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Mail\Mailer\Contract\Mailer;
use Valkyrja\Notification\Factory\ContainerFactory;
use Valkyrja\Notification\Factory\Contract\Factory;
use Valkyrja\Notification\Notifier\Contract\Notifier as NotifierContract;
use Valkyrja\Notification\Notifier\Notifier;
use Valkyrja\Sms\Messenger\Contract\Messenger;

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
            NotifierContract::class => [self::class, 'publishNotifier'],
            Factory::class          => [self::class, 'publishFactory'],
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
            NotifierContract::class,
            new Notifier(
                $container->getSingleton(Factory::class),
                $container->getSingleton(Broadcaster::class),
                $container->getSingleton(Mailer::class),
                $container->getSingleton(Messenger::class),
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
