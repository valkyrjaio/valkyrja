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

use Valkyrja\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Routing\Attribute\Contract\Attributes;
use Valkyrja\Http\Routing\Collector\Contract\Collector;
use Valkyrja\Http\Routing\Config as RoutingConfig;
use Valkyrja\Http\Routing\Config\Cache;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Support\Cacheable\Cacheable;

use function is_file;

/**
 * Class CacheableCollection.
 *
 * @author Melech Mizrachi
 */
class CacheableCollection extends Collection
{
    /**
     * @use Cacheable<RoutingConfig, array<string, mixed>, Cache>
     */
    use Cacheable;

    /**
     * CacheableCollection constructor.
     *
     * @param Container                          $container
     * @param RoutingConfig|array<string, mixed> $config
     */
    public function __construct(
        protected Container $container,
        protected RoutingConfig|array $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        $config          = new Cache();
        $config->routes  = [];
        $config->static  = $this->static;
        $config->dynamic = $this->dynamic;
        $config->named   = $this->named;

        foreach ($this->routes as $id => $route) {
            $config->routes[$id] = $route->asArray();
        }

        return $config;
    }

    /**
     * @inheritDoc
     *
     * @return RoutingConfig|array<string, mixed> $config The config
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     */
    protected function setupNotCached(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     */
    protected function setupFromCache(Config|array $config): void
    {
        $cache = $config['cache'] ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $config['cacheFilePath'];

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;
            }
        }

        $this->routes  = $cache['routes'] ?? [];
        $this->static  = $cache['static'] ?? [];
        $this->dynamic = $cache['dynamic'] ?? [];
        $this->named   = $cache['named'] ?? [];
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     *
     * @throws InvalidRoutePathException
     */
    protected function setupAttributes(Config|array $config): void
    {
        /** @var Attributes $routeAttributes */
        $routeAttributes = $this->container->getSingleton(Attributes::class);
        /** @var class-string[] $controllers */
        $controllers = $config['controllers'];

        // Get all the attributes routes from the list of controllers
        // Iterate through the routes
        foreach ($routeAttributes->getRoutes(...$controllers) as $route) {
            // Set the route
            $this->add($route);
        }
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     *
     * @throws InvalidRoutePathException
     */
    protected function afterSetup(Config|array $config): void
    {
        $this->dynamic = [];

        foreach ($this->routes as $route) {
            $this->setRouteToRequestMethods($route);
        }
    }

    /**
     * @inheritDoc
     *
     * @param RoutingConfig|array<string, mixed> $config The config
     */
    protected function requireFilePath(Config|array $config): void
    {
        $filePath = $config['filePath'] ?? null;

        if ($filePath === null) {
            return;
        }

        $collector = $this->container->getSingleton(Collector::class);

        if (is_file($filePath)) {
            require $filePath;
        }
    }
}
