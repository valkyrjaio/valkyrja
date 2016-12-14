<?php

namespace config\sub;

use Valkyrja\Config\Sub\AppConfig as ValkyrjaAppConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class AppConfig
 *
 * @package config\sub
 */
class AppConfig extends ValkyrjaAppConfig
{
    /**
     * AppConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
