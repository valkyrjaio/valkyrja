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

use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Path\PathParser;
use Valkyrja\Routing\Annotation\RouteAnnotator;
use Valkyrja\Routing\Config\Cache;
use Valkyrja\Routing\Config\Config as RoutingConfig;
use Valkyrja\Routing\Matcher;
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
     * The container.
     *
     * @var Container
     */
    protected Container $container;

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
     * @param Matcher    $matcher
     * @param PathParser $pathParser
     * @param array      $config
     */
    public function __construct(Container $container, Dispatcher $dispatcher, Matcher $matcher, PathParser $pathParser, array $config)
    {
        parent::__construct($dispatcher, $matcher, $pathParser);

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
        $config->routes  = $this->routes;
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

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
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        /** @var RouteAnnotator $routeAnnotations */
        $routeAnnotations = $this->container->getSingleton(RouteAnnotator::class);

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
     * @return void
     */
    protected function afterSetup(): void
    {
    }
}
