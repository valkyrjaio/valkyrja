<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Cacheables;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Routing\Annotation\RouteAnnotations;
use Valkyrja\Routing\Collections\RouteCollection;
use Valkyrja\Routing\Route;
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
     * Application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The route collection.
     *
     * @var RouteCollection
     */
    protected static RouteCollection $collection;

    /**
     * Get the config.
     *
     * @return array
     */
    protected function getConfig(): array
    {
        return $this->app->config(ConfigKeyPart::ROUTING);
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
        self::$collection = new RouteCollection($this->app);
    }

    /**
     * Setup the router from cache.
     *
     * @return void
     */
    protected function setupFromCache(): void
    {
        // Set the application routes with said file
        $cache = $this->app->config(ConfigKey::CACHE_ROUTING)
            ?? require $this->app->config(ConfigKey::ROUTING_CACHE_FILE_PATH);

        self::$collection = unserialize(
            base64_decode($cache[ConfigKeyPart::COLLECTION], true),
            [
                'allowed_classes' => [
                    RouteCollection::class,
                    Route::class,
                ],
            ]
        );
    }

    /**
     * Setup annotated routes.
     *
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setupAnnotations(): void
    {
        /** @var \Valkyrja\Routing\Annotation\RouteAnnotations $routeAnnotations */
        $routeAnnotations = $this->app->container()->getSingleton(RouteAnnotations::class);

        // Get all the annotated routes from the list of controllers
        $routes = $routeAnnotations->getRoutes(...$this->app->config(ConfigKey::ROUTING_CONTROLLERS));

        // Iterate through the routes
        foreach ($routes as $route) {
            // Set the route
            self::$collection->add($route);
        }
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return array
     */
    public function getCacheable(): array
    {
        $this->setup(true, false);

        return [
            ConfigKeyPart::COLLECTION => base64_encode(serialize(self::$collection)),
        ];
    }
}
