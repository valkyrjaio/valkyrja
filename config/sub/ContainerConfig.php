<?php

namespace config\sub;

use Valkyrja\Config\Sub\ContainerConfig as ValkyrjaContainerConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class ContainerConfig
 *
 * @package config\sub
 */
class ContainerConfig extends ValkyrjaContainerConfig
{
    /**
     * ContainerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
