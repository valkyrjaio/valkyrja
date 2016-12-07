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
            $this->views = new ViewsConfig($app);
        }
    }
}
