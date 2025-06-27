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

use Valkyrja\Api\Contract\Api;
use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;

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
    public static function publishers(): array
    {
        return [
            Api::class => [self::class, 'publishApi'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Api::class,
        ];
    }

    /**
     * Publish the api service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishApi(Container $container): void
    {
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            Api::class,
            new \Valkyrja\Api\Api(
                $container->getSingleton(ResponseFactory::class),
                $config->api,
                $config->app->debugMode
            )
        );
    }
}
