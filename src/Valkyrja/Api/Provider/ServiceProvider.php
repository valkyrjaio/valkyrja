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

namespace Valkyrja\Api\Provider;

use Override;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            ApiContract::class => [self::class, 'publishApi'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            ApiContract::class,
        ];
    }

    /**
     * Publish the api service.
     *
     * @param ContainerContract $container The container
     *
     * @return void
     */
    public static function publishApi(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $container->setSingleton(
            ApiContract::class,
            new Api(
                responseFactory: $container->getSingleton(ResponseFactoryContract::class),
                debug: $debugMode
            )
        );
    }
}
