<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Config\Env;
use Valkyrja\Support\Directory;

/**
 * Class RoutingConfig.
 *
 *
 * @author  Melech Mizrachi
 */
class RoutingConfig
{
    /**
     * Whether all routes should have trailing slashes.
     *
     * @var bool
     */
    public $trailingSlash = false;

    /**
     * Whether all route urls should be absolute.
     *
     * @var bool
     */
    public $useAbsoluteUrls = false;

    /**
     * Use annotations on controllers?
     *
     * @var bool
     */
    public $useAnnotations = false;

    /**
     * Use only annotations without routes file?
     *
     * @var bool
     */
    public $useAnnotationsExclusively = false;

    /**
     * Controllers to get annotations from.
     *
     * @var array
     */
    public $controllers = [];

    /**
     * The routes file path.
     *
     * @var string
     */
    public $filePath;

    /**
     * The routes cache file path.
     *
     * @var string
     */
    public $cacheFilePath;

    /**
     * Whether to use the routes cache file.
     *
     * @var bool
     */
    public $useCacheFile = false;

    /**
     * RoutingConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->trailingSlash             = $env::ROUTING_TRAILING_SLASH ?? $this->trailingSlash;
        $this->useAbsoluteUrls           = $env::ROUTING_USE_ABSOLUTE_URLS ?? $this->useAbsoluteUrls;
        $this->useAnnotations            = $env::ROUTING_USE_ANNOTATIONS ?? $this->useAnnotations;
        $this->useAnnotationsExclusively = $env::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY ?? $this->useAnnotationsExclusively;
        $this->controllers               = $env::ROUTING_CONTROLLERS ?? $this->controllers;
        $this->filePath                  = $env::ROUTING_FILE_PATH ?? Directory::routesPath('routes.php');
        $this->cacheFilePath             = $env::ROUTING_CACHE_FILE_PATH ?? Directory::storagePath('framework/cache/routes.php');
        $this->useCacheFile              = $env::ROUTING_USE_CACHE_FILE ?? $this->useCacheFile;
    }
}
