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
 * Class RoutingConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author Melech Mizrachi
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
     * Whether to allow slash and non slash ending urls.
     *
     * @var bool
     */
    public $allowWithTrailingSlash = false;

    /**
     * Use annotations on controllers?
     *
     * @var bool
     */
    public $useAnnotations = false;

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
    public $routesFile;

    /**
     * The routes cache file path.
     *
     * @var string
     */
    public $routesCacheFile;

    /**
     * Whether to use the routes cache file.
     *
     * @var bool
     */
    public $useRoutesCacheFile = false;

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * RoutingConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        if ($this->setDefaults) {
            $this->trailingSlash = $env::ROUTING_TRAILING_SLASH ?? false;
            $this->allowWithTrailingSlash = $env::ROUTING_ALLOW_WITH_TRAILING_SLASH ?? false;
            $this->useAnnotations = $env::ROUTING_USE_ANNOTATIONS ?? false;
            $this->controllers = $env::ROUTING_CONTROLLERS ?? [];
            $this->routesFile = $env::ROUTING_ROUTES_FILE ?? Directory::routesPath('routes.php');
            $this->routesCacheFile = $env::ROUTING_ROUTES_CACHE_FILE ?? Directory::storagePath('framework/routes.php');
            $this->useRoutesCacheFile = $env::ROUTING_USE_ROUTES_CACHE_FILE ?? false;
        }
    }
}
