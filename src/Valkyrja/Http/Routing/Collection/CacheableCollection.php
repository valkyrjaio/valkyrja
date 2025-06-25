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

namespace Valkyrja\Http\Routing\Collection;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Routing\Attribute\Contract\Collector;
use Valkyrja\Http\Routing\Config;
use Valkyrja\Http\Routing\Config\Cache;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;

/**
 * Class CacheableCollection.
 *
 * @author Melech Mizrachi
 */
class CacheableCollection extends Collection
{
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected bool $setup = false;

    /**
     * CacheableCollection constructor.
     */
    public function __construct(
        protected Container $container,
        protected Config $config
    ) {
    }

    /**
     * Setup the collection.
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If route's have already been setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        $this->setup = true;

        $cache = $this->config->cache;

        // If the application should use the routes cache
        if ($useCache && $cache !== null) {
            $this->setupFromCache($cache);

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
    }

    /**
     * Get a cacheable representation of the collection.
     */
    public function getCacheable(): Cache
    {
        $this->setup(true, false);

        $config          = new Cache();
        $config->routes  = [];
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

        foreach ($this->routes as $id => $route) {
            $config->routes[$id] = serialize($route);
        }

        return $config;
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(Cache $cache): void
    {
        $this->routes  = $cache->routes;
        $this->static  = $cache->static;
        $this->dynamic = $cache->dynamic;
        $this->named   = $cache->named;
    }

    /**
     * Setup not cache.
     */
    protected function setupNotCached(): void
    {
        $this->setupAttributedControllers();
        $this->afterSetup();
    }

    /**
     * Get attributed controllers.
     *
     * @throws InvalidRoutePathException
     */
    protected function setupAttributedControllers(): void
    {
        /** @var Collector $collector */
        $collector   = $this->container->getSingleton(Collector::class);
        $controllers = $this->config->controllers;

        // Get all the attributes routes from the list of controllers
        // Iterate through the routes
        foreach ($collector->getRoutes(...$controllers) as $route) {
            // Set the route
            $this->add($route);
        }
    }

    /**
     * Do after setup.
     *
     * @throws InvalidRoutePathException
     */
    protected function afterSetup(): void
    {
        $this->dynamic = [];

        /** @var Route $route */
        foreach ($this->routes as $route) {
            $this->setRouteToRequestMethods($route);
        }
    }
}
