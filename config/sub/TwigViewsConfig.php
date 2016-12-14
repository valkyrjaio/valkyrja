<?php

namespace config\sub;

use Valkyrja\Config\Sub\TwigViewsConfig as ValkyrjaTwigViewsConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class TwigViewsConfig
 *
 * @package config\sub
 */
class TwigViewsConfig extends ValkyrjaTwigViewsConfig
{
    /**
     * TwigViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
