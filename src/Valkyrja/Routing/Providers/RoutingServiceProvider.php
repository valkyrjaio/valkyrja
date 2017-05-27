<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Routing\Router;
use Valkyrja\Support\Provider;

/**
 * Class RoutingServiceProvider.
 *
 * @author Melech Mizrachi
 */
class RoutingServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::ROUTER,
    ];

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindRouter($app);

        $app->router()->setup();
    }

    /**
     * Bind the router.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindRouter(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::ROUTER,
            new Router($app)
        );
    }
}
