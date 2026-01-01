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

namespace Valkyrja\Dispatch\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            DispatcherContract::class => [self::class, 'publishDispatcher'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            DispatcherContract::class,
        ];
    }

    /**
     * Publish the dispatcher service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishDispatcher(ContainerContract $container): void
    {
        $container->setSingleton(
            DispatcherContract::class,
            new Dispatcher(
                container: $container
            )
        );
    }
}
