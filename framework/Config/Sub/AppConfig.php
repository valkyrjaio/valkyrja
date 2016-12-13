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

use Valkyrja\Contracts\Application;

/**
 * Class AppConfig
 *
 * @package Valkyrja\Config\Sub
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
            $env = $app->env();

            $this->env = $env::APP_ENV
                ?? 'production';
            $this->debug = $env::APP_DEBUG
                ?? false;
            $this->url = $env::APP_URL
                ?? 'localhost';
            $this->timezone = $env::APP_TIMEZONE
                ?? 'UTC';
            $this->version = $env::APP_VERSION
                ?? '1.0';
        }
    }
}
