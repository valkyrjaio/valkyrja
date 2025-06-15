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
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Routing\Attribute\Contract\Attributes;
use Valkyrja\Http\Routing\Config;
use Valkyrja\Http\Routing\Config\Cache;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Model\Contract\Route;

use function is_file;

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
        // The cacheable config
        $config = $this->config;

        $configUseCache = $config->useCache;

        // If the application should use the routes cache file
        if ($useCache && $configUseCache) {
            $this->setupFromCache();

            // Then return out of setup
            return;
        }

        $this->setupAttributedControllers();
        $this->requireFilePath();
        $this->afterSetup();
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
    protected function setupFromCache(): void
    {
        $cache = $this->config->cache ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $this->config->cacheFilePath;

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;

                if (! $cache instanceof Cache) {
                    throw new RuntimeException('Invalid cache object returned');
                }
            } else {
                throw new RuntimeException('No cache found');
            }
        }

        $this->routes  = $cache->routes;
        $this->static  = $cache->static;
        $this->dynamic = $cache->dynamic;
        $this->named   = $cache->named;
    }

    /**
     * Get attributed controllers.
     *
     * @throws InvalidRoutePathException
     */
    protected function setupAttributedControllers(): void
    {
        /** @var Attributes $routeAttributes */
        $routeAttributes = $this->container->getSingleton(Attributes::class);
        $controllers     = $this->config->controllers;

        // Get all the attributes routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAttributes->getRoutes(...$controllers) as $route) {
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

    /**
     * Require the file path specified in the config.
     */
    protected function requireFilePath(): void
    {
        // $filePath = $this->config->filePath;
        //
        // if (is_file($filePath)) {
        //     $collector = $this->container->getSingleton(Collector::class);
        //
        //     require $filePath;
        // }
    }
}
