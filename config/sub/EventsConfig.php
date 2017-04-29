<?php

namespace config\sub;

use App\Controllers\HomeController;

use Valkyrja\Config\Sub\EventsConfig as ValkyrjaEventsConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class EventsConfig
 *
 * @package config\sub
 */
class EventsConfig extends ValkyrjaEventsConfig
{
    /**
     * EventsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->classes = [
            HomeController::class,
        ];
    }
}
