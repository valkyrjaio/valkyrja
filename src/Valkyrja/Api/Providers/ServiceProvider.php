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

namespace Valkyrja\Api\Providers;

use Valkyrja\Api\Api;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\JsonResponse;

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
            Api::class => 'publishApi',
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
            Api::class,
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
     * Publish the api service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishApi(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Api::class,
            new \Valkyrja\Api\Apis\Api(
                $container->getSingleton(JsonResponse::class),
                (array) $config['api'],
                $config['app']['debug']
            )
        );
    }
}
