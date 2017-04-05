<?php

namespace config\sub;

use Valkyrja\Config\Sub\LoggerConfig as ValkyrjaLoggerConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class LoggerConfig
 *
 * @package config\sub
 */
class LoggerConfig extends ValkyrjaLoggerConfig
{
    /**
     * LoggerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
