<?php

namespace config;

use config\sub\AppConfig;
use config\sub\ModelsConfig;
use config\sub\StorageConfig;
use config\sub\ViewsConfig;

use Valkyrja\Contracts\Application;
use Valkyrja\Config\Config as ValkyrjaConfig;

/**
 * Class Config
 *
 * @package config
 */
class Config extends ValkyrjaConfig
{
    /**
     * Models config.
     *
     * @var ModelsConfig
     */
    public $models;

    /**
     * Which env file to use.
     *
     * @var string
     */
    const ENV_CLASS_NAME = Env::class;

    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->app = new AppConfig($app);
        $this->models = new ModelsConfig($app);
        $this->storage = new StorageConfig($app);
        $this->views = new ViewsConfig($app);
    }
}
