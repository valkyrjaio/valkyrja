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
use Valkyrja\Config\Models\CacheableConfig as Model;

/**
 * Class RoutingConfig.
 *
 * @author Melech Mizrachi
 */
class RoutingConfig extends Model
{
    public array $middleware       = [];
    public array $middlewareGroups = [];
    public array $controllers      = [];
    public bool  $useTrailingSlash = false;
    public bool  $useAbsoluteUrls  = false;

    /**
     * RoutingConfig constructor.
     */
    public function __construct()
    {
        $this->middleware       = (array) env(EnvKey::ROUTING_MIDDLEWARE, $this->controllers);
        $this->middlewareGroups = (array) env(EnvKey::ROUTING_MIDDLEWARE_GROUPS, $this->controllers);
        $this->controllers      = (array) env(EnvKey::ROUTING_CONTROLLERS, $this->controllers);
        $this->useTrailingSlash = (bool) env(EnvKey::ROUTING_TRAILING_SLASH, $this->useTrailingSlash);
        $this->useAbsoluteUrls  = (bool) env(EnvKey::ROUTING_USE_ABSOLUTE_URLS, $this->useAbsoluteUrls);

        $this->envUseAnnotationsKey            = EnvKey::ROUTING_USE_ANNOTATIONS;
        $this->envUseAnnotationsExclusivelyKey = EnvKey::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY;
        $this->setAnnotationsConfig();

        $this->filePath            = routesPath('default.php');
        $this->cacheFilePath       = cachePath('routing.php');
        $this->envFilePathKey      = EnvKey::ROUTING_FILE_PATH;
        $this->envCacheFilePathKey = EnvKey::ROUTING_CACHE_FILE_PATH;
        $this->envUseCacheKey      = EnvKey::ROUTING_USE_CACHE_FILE;
        $this->setCacheableConfig();
    }
}
