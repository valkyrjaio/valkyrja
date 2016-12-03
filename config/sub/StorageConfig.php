<?php

namespace config\sub;

use Valkyrja\Config\Sub\StorageConfig as ValkyrjaStorageConfig;
use Valkyrja\Contracts\Application;

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
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}
