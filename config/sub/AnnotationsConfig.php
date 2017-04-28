<?php

namespace config\sub;

use Valkyrja\Config\Sub\AnnotationsConfig as ValkyrjaAnnotationsConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class AnnotationsConfig
 *
 * @package config\sub
 */
class AnnotationsConfig extends ValkyrjaAnnotationsConfig
{
    /**
     * AnnotationsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
