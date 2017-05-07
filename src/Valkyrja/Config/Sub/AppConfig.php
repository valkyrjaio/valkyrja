<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Sub;

use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Events\Events;

/**
 * Class AppConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author  Melech Mizrachi
 */
class AppConfig
{
    /**
     * Environment application is running in.
     *
     * @var string
     */
    public $env = 'production';

    /**
     * Is the application in debug?
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Application url.
     *
     * @var string
     */
    public $url = 'localhost';

    /**
     * Timezone.
     *
     * @var string
     */
    public $timezone = 'UTC';

    /**
     * Application version.
     *
     * @var string
     */
    public $version = Application::VERSION;

    /**
     * The container implementation.
     *
     * @var string
     */
    public $container = Container::class;

    /**
     * The events implementation.
     *
     * @var string
     */
    public $events = Events::class;

    /**
     * The regex map.
     *
     * @var array
     */
    public $pathRegexMap = [
        'num'                  => '(\d+)',
        'slug'                 => '([a-zA-Z0-9-]+)',
        'alpha'                => '([a-zA-Z]+)',
        'alpha-lowercase'      => '([a-z]+)',
        'alpha-uppercase'      => '([A-Z]+)',
        'alpha-num'            => '([a-zA-Z0-9]+)',
        'alpha-num-underscore' => '(\w+)',
    ];

    /**
     * AppConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->env = $env::APP_ENV
            ?? $this->env;
        $this->debug = $env::APP_DEBUG
            ?? $this->debug;
        $this->url = $env::APP_URL
            ?? $this->url;
        $this->timezone = $env::APP_TIMEZONE
            ?? $this->timezone;
        $this->version = $env::APP_VERSION
            ?? $this->version;

        $this->container = $env::APP_CONTAINER
            ?? $this->container;
        $this->events = $env::APP_EVENTS
            ?? $this->events;
    }
}
