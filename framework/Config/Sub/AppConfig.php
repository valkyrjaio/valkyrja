<?php

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Application;
use Valkyrja\Support\Helpers;

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
            $this->env = Helpers::env('APP_ENV') ?? 'production';
            $this->debug = Helpers::env('APP_DEBUG') ?? false;
            $this->url = Helpers::env('APP_URL') ?? 'localhost';
            $this->timezone = Helpers::env('APP_TIMEZONE') ?? 'UTC';
            $this->version = Helpers::env('APP_VERSION') ?? '1.0';
        }
    }
}
