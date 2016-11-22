<?php

namespace Valkyrja\Config;

use config\Env;

use Valkyrja\Config\Sub\AppConfig;
use Valkyrja\Config\Sub\StorageConfig;
use Valkyrja\Config\Sub\ViewsConfig;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Config as ConfigContract;

class Config implements ConfigContract
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
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $this->app = new AppConfig($app);
            $this->storage = new StorageConfig($app);
            $this->views = new ViewsConfig($app);
        }
    }

    /**
     * Get the config global instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public static function config() : ConfigContract
    {
        global $config;

        return $config;
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
