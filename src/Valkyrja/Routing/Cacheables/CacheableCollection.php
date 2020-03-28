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

namespace Valkyrja\Routing\Cacheables;

use Valkyrja\Config\Configs\Routing;
use Valkyrja\Routing\Annotation\RouteAnnotator;
use Valkyrja\Support\Cacheables\Cacheable;

use function Valkyrja\config;
use function Valkyrja\container;

/**
 * Trait CacheableCollection.
 *
 * @author Melech Mizrachi
 */
trait CacheableCollection
{
    use Cacheable;

    /**
     * Get the config.
     *
     * @return Routing|array
     */
    protected function getConfig()
    {
        return config()['routing'];
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
     * @param Routing|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        $this->routes  = $cache['routes'];
        $this->static  = $cache['static'];
        $this->dynamic = $cache['dynamic'];
        $this->named   = $cache['named'];
    }

    /**
     * Setup annotated routes.
     *
     * @param Routing|array $config
     *
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        /** @var RouteAnnotator $routeAnnotations */
        $routeAnnotations = container()->getSingleton(RouteAnnotator::class);

        // Get all the annotated routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAnnotations->getRoutes(...$config['controllers']) as $route) {
            // Set the route
            $this->add($route);
        }
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return Cache
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config          = new Cache();
        $config->routes  = $this->routes;
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

        return $config;
    }
}
