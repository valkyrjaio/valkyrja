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

use Valkyrja\Config\Sub\AnnotationsConfig;
use Valkyrja\Config\Sub\AppConfig;
use Valkyrja\Config\Sub\RoutingConfig;
use Valkyrja\Config\Sub\StorageConfig;
use Valkyrja\Config\Sub\ViewsConfig;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Config\Env;

/**
 * Class Config
 *
 * @package Valkyrja\Config
 *
 * @author  Melech Mizrachi
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
     * Annotations config.
     *
     * @var AnnotationsConfig
     */
    public $annotations;

    /**
     * Environment variables.
     *
     * @var \Valkyrja\Contracts\Config\Env
     */
    public $env;

    /**
     * Routing config.
     *
     * @var RoutingConfig
     */
    public $routing;

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
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->env = $env;

        $this->app = new AppConfig($env);
        $this->annotations = new AnnotationsConfig($env);
        $this->routing = new RoutingConfig($env);
        $this->storage = new StorageConfig($env);
        $this->views = new ViewsConfig($env);
    }
}
