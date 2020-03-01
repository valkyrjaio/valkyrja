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

use Valkyrja\Application\Application;
use Valkyrja\Config\Configs\RoutingConfig;
use Valkyrja\Routing\Annotation\RouteAnnotator;
use Valkyrja\Routing\Collections\Collection;
use Valkyrja\Routing\Matchers\Matcher;
use Valkyrja\Routing\Models\Route;
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
     * The route collection.
     *
     * @var Collection
     */
    protected static Collection $collection;

    /**
     * Application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Get the config.
     *
     * @return RoutingConfig|object
     */
    protected function getConfig(): object
    {
        return $this->app->config()->routing;
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
        self::$collection = new Collection();
    }

    /**
     * Setup the router from cache.
     *
     * @param RoutingConfig|object $config
     *
     * @return void
     */
    protected function setupFromCache(object $config): void
    {
        /** @var CacheConfig $cache */
        $cache = $config->cache ?? require $config->cacheFilePath;

        self::$collection = unserialize(
            base64_decode($cache->collection, true),
            [
                'allowed_classes' => [
                    Matcher::class,
                    Collection::class,
                    Route::class,
                ],
            ]
        );
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
        $routeAnnotations = $this->app->container()->getSingleton(RouteAnnotator::class);

        // Get all the annotated routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAnnotations->getRoutes(...$config->controllers) as $route) {
            // Set the route
            self::$collection->add($route);
        }
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return CacheConfig
     */
    public function getCacheable(): CacheConfig
    {
        $this->setup(true, false);

        $config             = new CacheConfig();
        $config->collection = base64_encode(serialize(self::$collection));

        return $config;
    }
}
