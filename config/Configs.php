<?php

namespace config;

use config\config\AppConfig;
use config\config\ModelsConfig;
use config\config\StorageConfig;
use config\config\ViewsConfig;

use Valkyrja\Application;

class Configs
{
    /**
     * Application config.
     *
     * @var AppConfig
     */
    public $app;

    /**
     * Application config.
     *
     * @var ModelsConfig
     */
    public $models;

    /**
     * Application config.
     *
     * @var StorageConfig
     */
    public $storage;

    /**
     * Application config.
     *
     * @var ViewsConfig
     */
    public $views;

    /**
     * Configs constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = new AppConfig($app);
        $this->models = new ModelsConfig($app);
        $this->storage = new StorageConfig($app);
        $this->views = new ViewsConfig($app);
    }

    /**
     * Get an environment variable.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function env(string $key) // : mixed
    {
        $key = Env::class . '::' . $key;

        if (defined($key)) {
            return constant($key);
        }

        return null;
    }
}
