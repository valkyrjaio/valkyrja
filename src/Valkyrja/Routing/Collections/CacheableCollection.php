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

namespace Valkyrja\Routing\Collections;

use JsonException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Routing\Annotation\Annotator;
use Valkyrja\Routing\Config\Cache;
use Valkyrja\Routing\Config\Config as RoutingConfig;
use Valkyrja\Support\Cacheable\Cacheable;

/**
 * Class CacheableCollection.
 *
 * @author Melech Mizrachi
 */
class CacheableCollection extends Collection
{
    use Cacheable;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * RouteCollection constructor.
     *
     * @param Container  $container
     * @param Dispatcher $dispatcher
     * @param array      $config
     */
    public function __construct(Container $container, Dispatcher $dispatcher, array $config)
    {
        parent::__construct($container, $dispatcher);

        $this->container = $container;
        $this->config    = $config;
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
        $config->routes  = [];
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

        foreach ($this->routes as $id => $route) {
            $config->routes[$id] = $route->__toArray();
        }

        return $config;
    }

    /**
     * Get the config.
     *
     * @return RoutingConfig|array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Before setup.
     *
     * @param RoutingConfig|array $config
     *
     * @return void
     */
    protected function beforeSetup($config): void
    {
    }

    /**
     * Set not cached.
     *
     * @param RoutingConfig|array $config
     *
     * @return void
     */
    protected function setupNotCached($config): void
    {
    }

    /**
     * Setup the router from cache.
     *
     * @param RoutingConfig|array $config
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
     * @param RoutingConfig|array $config
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        /** @var Annotator $routeAnnotations */
        $routeAnnotations = $this->container->getSingleton(Annotator::class);

        // Get all the annotated routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAnnotations->getRoutes(...$config['controllers']) as $route) {
            // Set the route
            $this->add($route);
        }
    }

    /**
     * After setup.
     *
     * @param RoutingConfig|array $config
     *
     * @return void
     */
    protected function afterSetup($config): void
    {
    }
}
