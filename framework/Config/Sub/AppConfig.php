<?php

namespace Valkyrja\Config\Sub;

use Valkyrja\Config\Config;

use Valkyrja\Contracts\Application;

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
    public $version = '1.0';

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * AppConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $this->env = Config::env('APP_ENV') ?? 'production';
            $this->debug = Config::env('APP_DEBUG') ?? false;
            $this->url = Config::env('APP_URL') ?? 'localhost';
            $this->timezone = Config::env('APP_TIMEZONE') ?? 'UTC';
            $this->version = Config::env('APP_VERSION') ?? '1.0';
        }
    }
}
