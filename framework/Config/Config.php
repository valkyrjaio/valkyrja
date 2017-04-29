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
use Valkyrja\Config\Sub\ContainerConfig;
use Valkyrja\Config\Sub\EventsConfig;
use Valkyrja\Config\Sub\LoggerConfig;
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
     * @var \Valkyrja\Config\Sub\AppConfig
     */
    public $app;

    /**
     * Annotations config.
     *
     * @var \Valkyrja\Config\Sub\AnnotationsConfig
     */
    public $annotations;

    /**
     * Container config.
     *
     * @var \Valkyrja\Config\Sub\ContainerConfig
     */
    public $container;

    /**
     * Environment variables.
     *
     * @var \Valkyrja\Contracts\Config\Env
     */
    public $env;

    /**
     * Events config.
     *
     * @var \Valkyrja\Config\Sub\EventsConfig
     */
    public $events;

    /**
     * Logger config.
     *
     * @var \Valkyrja\Config\Sub\LoggerConfig
     */
    public $logger;

    /**
     * Routing config.
     *
     * @var \Valkyrja\Config\Sub\RoutingConfig
     */
    public $routing;

    /**
     * Storage config.
     *
     * @var \Valkyrja\Config\Sub\StorageConfig
     */
    public $storage;

    /**
     * Views config.
     *
     * @var \Valkyrja\Config\Sub\ViewsConfig
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

        $this->annotations = new AnnotationsConfig($env);
        $this->app = new AppConfig($env);
        $this->container = new ContainerConfig($env);
        $this->events = new EventsConfig($env);
        $this->logger = new LoggerConfig($env);
        $this->routing = new RoutingConfig($env);
        $this->storage = new StorageConfig($env);
        $this->views = new ViewsConfig($env);
    }
}
