<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Api\Provider;

use Override;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

class ApiServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the api service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishApi(ContainerContract $container): void
    {
        $app = $container->getSingleton(ApplicationContract::class);

        $container->setSingleton(
            ApiContract::class,
            new Api(
                responseFactory: $container->getSingleton(ResponseFactoryContract::class),
                debug: $app->getDebugMode()
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
            ApiContract::class => [self::class, 'publishApi'],
        ];
    }
}
