<?php

namespace config\sub;

use Valkyrja\Config\Sub\StorageConfig as ValkyrjaStorageConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class StorageConfig
 *
 * @package config\sub
 */
class StorageConfig extends ValkyrjaStorageConfig
{
    /**
     * StorageConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);
    }
}
