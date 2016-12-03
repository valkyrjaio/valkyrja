<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config;

use Valkyrja\Config\Sub\AppConfig;
use Valkyrja\Config\Sub\RoutingConfig;
use Valkyrja\Config\Sub\StorageConfig;
use Valkyrja\Config\Sub\ViewsConfig;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Config as ConfigContract;

/**
 * Class Config
 *
 * @package Valkyrja\Config
 */
class Config implements ConfigContract
{
    /**
     * Application config.
     *
     * @var AppConfig
     */
    public $app;

    /**
     * Storage config.
     *
     * @var StorageConfig
     */
    public $storage;

    /**
     * Routing config.
     *
     * @var RoutingConfig
     */
    public $routing;

    /**
     * Views config.
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
            $this->routing = new RoutingConfig($app);
            $this->views = new ViewsConfig($app);
        }
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
        $key = static::ENV_CLASS_NAME . '::' . $key;

        if (defined($key)) {
            return constant($key);
        }

        return null;
    }
}
