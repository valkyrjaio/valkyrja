<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Support\Provider;

/**
 * Class JsonResponseServiceProvider.
 *
 * @author Melech Mizrachi
 */
class JsonResponseServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::JSON_RESPONSE,
    ];

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return static::$provides;
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindJsonResponse($app);
    }

    /**
     * Bootstrap the json response.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected static function bindJsonResponse(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::JSON_RESPONSE,
            new JsonResponse()
        );
    }
}
