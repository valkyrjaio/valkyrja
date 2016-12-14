<?php

namespace config\sub;

use Valkyrja\Config\Sub\ViewsConfig as ValkyrjaViewsConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class ViewsConfig
 *
 * @package config\sub
 */
class ViewsConfig extends ValkyrjaViewsConfig
{
    /**
     * ViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->twig = new TwigViewsConfig($env);
    }
}
