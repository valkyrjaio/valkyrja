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

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Cacheable as Model;

use function env;

/**
 * Class Routing
 *
 * @author Melech Mizrachi
 */
class Routing extends Model
{
    /**
     * The middleware.
     *
     * @var array
     */
    public array $middleware;

    /**
     * The middleware groups.
     *
     * @var array
     */
    public array $middlewareGroups;

    /**
     * The annotated controllers.
     *
     * @var array
     */
    public array $controllers;

    /**
     * The flag to enable trailing slashes for all urls.
     *
     * @var bool
     */
    public bool $useTrailingSlash;

    /**
     * The flag to enable absolute urls.
     *
     * @var bool
     */
    public bool $useAbsoluteUrls;

    /**
     * Routing constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setMiddleware();
        $this->setMiddlewareGroups();
        $this->setControllers();
        $this->setUseTrailingSlash();
        $this->setUseAbsoluteUrls();

        $this->setFilePathEnvKey(EnvKey::ROUTING_FILE_PATH);
        $this->setCacheFilePathEnvKey(EnvKey::ROUTING_CACHE_FILE_PATH);
        $this->setUseCacheEnvKey(EnvKey::ROUTING_USE_CACHE_FILE);
        $this->setUseAnnotationsEnvKey(EnvKey::ROUTING_USE_ANNOTATIONS);
        $this->setUseAnnotationsExclusivelyEnvKey(EnvKey::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY);

        $this->setFilePath(routesPath('default.php'));
        $this->setCacheFilePath(cachePath('routing.php'));
        $this->setUseCache();
        $this->setAnnotationsConfig();
    }

    /**
     * Set the middleware.
     *
     * @param array $middleware [optional] The middleware
     *
     * @return void
     */
    protected function setMiddleware(array $middleware = []): void
    {
        $this->middleware = (array) env(EnvKey::ROUTING_MIDDLEWARE, $middleware);
    }

    /**
     * Set the middleware groups.
     *
     * @param array $middlewareGroups [optional] The middleware groups
     *
     * @return void
     */
    protected function setMiddlewareGroups(array $middlewareGroups = []): void
    {
        $this->middlewareGroups = (array) env(EnvKey::ROUTING_MIDDLEWARE_GROUPS, $middlewareGroups);
    }

    /**
     * Set the annotated controllers.
     *
     * @param array $controllers [optional] The annotated controllers
     *
     * @return void
     */
    protected function setControllers(array $controllers = []): void
    {
        $this->controllers = (array) env(EnvKey::ROUTING_CONTROLLERS, $controllers);
    }

    /**
     * Set the flag to enable trailing slashes for all urls.
     *
     * @param bool $useTrailingSlash [optional] The flag to enable trailing slashes for all urls
     *
     * @return void
     */
    protected function setUseTrailingSlash(bool $useTrailingSlash = false): void
    {
        $this->useTrailingSlash = (bool) env(EnvKey::ROUTING_TRAILING_SLASH, $useTrailingSlash);
    }

    /**
     * Set the flag to enable absolute urls.
     *
     * @param bool $useAbsoluteUrls [optional] The flag to enable absolute urls
     *
     * @return void
     */
    protected function setUseAbsoluteUrls(bool $useAbsoluteUrls = false): void
    {
        $this->useAbsoluteUrls  = (bool) env(EnvKey::ROUTING_USE_ABSOLUTE_URLS, $useAbsoluteUrls);
    }
}
