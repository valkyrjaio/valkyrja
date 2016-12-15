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
            $this->useAnnotations = $env::ROUTING_USE_ANNOTATIONS ?? false;
            $this->routesFile = Directory::routesPath('routes.php');
            $this->routesCacheFile = Directory::storagePath('framework/routes.php');
        }
    }
}
