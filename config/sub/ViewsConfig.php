<?php

namespace config\sub;

use Valkyrja\Config\Sub\ViewsConfig as ValkyrjaViewsConfig;
use Valkyrja\Contracts\Application;

class ViewsConfig extends ValkyrjaViewsConfig
{
    /**
     * ViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->twig = new TwigViewsConfig($app);
    }
}
