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

namespace Valkyrja\Http\Message\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Factory\ResponseFactory;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            ResponseFactoryContract::class => [self::class, 'publishResponseFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            ResponseFactoryContract::class,
        ];
    }

    /**
     * Publish the response factory service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseFactory(ContainerContract $container): void
    {
        $container->setSingleton(
            ResponseFactoryContract::class,
            new ResponseFactory()
        );
    }
}
