<?php

namespace config\sub;

use Valkyrja\Config\Sub\TwigViewsConfig as ValkyrjaTwigViewsConfig;
use Valkyrja\Application;

class TwigViewsConfig extends ValkyrjaTwigViewsConfig
{
    /**
     * TwigViewsConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
