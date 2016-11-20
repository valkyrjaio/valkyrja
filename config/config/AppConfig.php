<?php

namespace config\config;

use config\Configs;

use Valkyrja\Application;

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
     * AppConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->env = Configs::env('APP_ENV') ?? 'production';
        $this->debug = Configs::env('APP_DEBUG') ?? false;
        $this->url = Configs::env('APP_URL') ?? 'localhost';
        $this->timezone = Configs::env('APP_TIMEZONE') ?? 'UTC';
        $this->version = Configs::env('APP_VERSION') ?? '1.0';
    }
}
