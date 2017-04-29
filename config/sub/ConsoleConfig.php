<?php

namespace config\sub;

use Valkyrja\Config\Sub\ConsoleConfig as ValkyrjaConsoleConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class ConsoleConfig
 *
 * @package config\sub
 */
class ConsoleConfig extends ValkyrjaConsoleConfig
{
    /**
     * ConsoleConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->handlers = [];
    }
}
