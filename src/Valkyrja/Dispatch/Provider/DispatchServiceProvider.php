<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;

class DispatchServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the dispatcher service.
     *
     * @param ContainerContract $container The container
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            DispatcherContract::class => [self::class, 'publishDispatcher'],
        ];
    }
}
