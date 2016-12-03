<?php

namespace config\sub;

use Valkyrja\Config\Sub\RoutingConfig as ValkyrjaRoutingConfig;
use Valkyrja\Contracts\Application;

/**
 * Class RoutingConfig
 *
 * @package config\sub
 */
class RoutingConfig extends ValkyrjaRoutingConfig
{
    /**
     * RoutingConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
