<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Events\Events;

/*
 *-------------------------------------------------------------------------
 * Application Configuration
 *-------------------------------------------------------------------------
 *
 * This part of the configuration has to do with the base configuration
 * settings for the application as a whole.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Application Environment
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'env'          => env()::APP_ENV ?? 'production',

    /*
     *-------------------------------------------------------------------------
     * Application Debug
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'debug'        => env()::APP_DEBUG ?? false,

    /*
     *-------------------------------------------------------------------------
     * Application Url
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'url'          => env()::APP_URL ?? 'localhost',

    /*
     *-------------------------------------------------------------------------
     * Application Timezone
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'timezone'     => env()::APP_TIMEZONE ?? 'UTC',

    /*
     *-------------------------------------------------------------------------
     * Application Version
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'version'      => env()::APP_VERSION ?? Application::VERSION,

    /*
     *-------------------------------------------------------------------------
     * Application Container Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'container'    => env()::APP_CONTAINER ?? Container::class,

    /*
     *-------------------------------------------------------------------------
     * Application Dispatcher Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'dispatcher'   => env()::APP_DISPATCHER ?? Dispatcher::class,

    /*
     *-------------------------------------------------------------------------
     * Application Events Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'events'       => env()::APP_EVENTS ?? Events::class,

    /*
     *-------------------------------------------------------------------------
     * Application Path Regex Map
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'pathRegexMap' => env()::APP_PATH_REGEX_MAP ?? [
            'num'                  => '(\d+)',
            'slug'                 => '([a-zA-Z0-9-]+)',
            'alpha'                => '([a-zA-Z]+)',
            'alpha-lowercase'      => '([a-z]+)',
            'alpha-uppercase'      => '([A-Z]+)',
            'alpha-num'            => '([a-zA-Z0-9]+)',
            'alpha-num-underscore' => '(\w+)',
        ],
];