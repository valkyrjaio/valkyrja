<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Cacheables;

use Valkyrja\Config\Configs\RoutingConfig;
use Valkyrja\Routing\Annotation\RouteAnnotator;
use Valkyrja\Support\Cacheables\Cacheable;

/**
 * Trait CacheableRouter.
 *
 * @author Melech Mizrachi
 */
trait CacheableRouter
{
    use Cacheable;

    /**
     * Get the config.
     *
     * @return RoutingConfig|object
     */
    protected function getConfig()
    {
        return config('routing');
    }

    /**
     * Before setup.
     *
     * @return void
     */
    protected function beforeSetup(): void
    {
    }

    /**
     * Set not cached.
     *
     * @return void
     */
    protected function setupNotCached(): void
    {
    }

    /**
     * Setup the router from cache.
     *
     * @param RoutingConfig|object $config
     *
     * @return void
     */
    protected function setupFromCache($config): void
    {
        /** @var CacheConfig $cache */
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        $this->routes  = $cache['routes'];
        $this->static  = $cache['static'];
        $this->dynamic = $cache['dynamic'];
        $this->named   = $cache['named'];
    }

    /**
     * Setup annotated routes.
     *
     * @param RoutingConfig|object $config
     *
     * @return void
     */
    protected function setupAnnotations(object $config): void
    {
        /** @var RouteAnnotator $routeAnnotations */
        $routeAnnotations = container()->getSingleton(RouteAnnotator::class);

        // Get all the annotated routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAnnotations->getRoutes(...$config->controllers) as $route) {
            // Set the route
            $this->add($route);
        }
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return CacheConfig|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config          = new CacheConfig();
        $config->routes  = $this->routes;
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

        return $config;
    }
}
