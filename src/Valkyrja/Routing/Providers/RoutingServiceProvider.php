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
use Valkyrja\Container\Service;
use Valkyrja\Routing\Router;
use Valkyrja\Support\ServiceProvider;

/**
 * Class RoutingServiceProvider.
 *
 * @author Melech Mizrachi
 */
class RoutingServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindRouter();

        $this->app->router()->setup();
    }

    /**
     * Bind the router.
     *
     * @return void
     */
    protected function bindRouter(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::ROUTER)
                ->setClass(Router::class)
                ->setDependencies([CoreComponent::APP, CoreComponent::PATH_PARSER, CoreComponent::PATH_GENERATOR])
        );
    }
}
