<?php

namespace config\sub;

use Valkyrja\Config\Sub\AppConfig as ValkyrjaAppConfig;
use Valkyrja\Contracts\Application;

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
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
