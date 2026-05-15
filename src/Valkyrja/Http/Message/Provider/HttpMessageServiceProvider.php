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
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Factory\ResponseFactory;

class HttpMessageServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ResponseFactoryContract::class => [self::class, 'publishResponseFactory'],
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
