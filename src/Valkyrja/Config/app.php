<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Application Configuration
 *-------------------------------------------------------------------------
 *
 * This part of the configuration has to do with the base configuration
 * settings for the application as a whole.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Application Environment
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ENV                  => env(EnvKey::APP_ENV, 'production'),

    /*
     *-------------------------------------------------------------------------
     * Application Debug
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEBUG                => env(EnvKey::APP_DEBUG, false),

    /*
     *-------------------------------------------------------------------------
     * Application Url
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::URL                  => env(EnvKey::APP_URL, 'localhost'),

    /*
     *-------------------------------------------------------------------------
     * Application Timezone
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::TIMEZONE             => env(EnvKey::APP_TIMEZONE, 'UTC'),

    /*
     *-------------------------------------------------------------------------
     * Application Version
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::VERSION              => env(EnvKey::APP_VERSION, Valkyrja\Application::VERSION),

    /*
     *-------------------------------------------------------------------------
     * Application Key
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::KEY                  => env(EnvKey::APP_KEY, 'some_secret_app_key'),

    /*
     *-------------------------------------------------------------------------
     * Application Http Exception Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::HTTP_EXCEPTION_CLASS => env(EnvKey::APP_HTTP_EXCEPTION_CLASS, \Valkyrja\Http\Exceptions\HttpException::class),

    /*
     *-------------------------------------------------------------------------
     * Application Container Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONTAINER            => env(EnvKey::APP_CONTAINER, Valkyrja\Container\NativeContainer::class),

    /*
     *-------------------------------------------------------------------------
     * Application Dispatcher Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DISPATCHER           => env(EnvKey::APP_DISPATCHER, Valkyrja\Dispatcher\NativeDispatcher::class),

    /*
     *-------------------------------------------------------------------------
     * Application Events Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::EVENTS               => env(EnvKey::APP_EVENTS, Valkyrja\Events\NativeEvents::class),

    /*
     *-------------------------------------------------------------------------
     * Application ExceptionHandler Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::EXCEPTION_HANDLER    => env(EnvKey::APP_EXCEPTION_HANDLER, Valkyrja\Exceptions\NativeExceptionHandler::class),

    /*
     *-------------------------------------------------------------------------
     * Application Path Regex Map
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PATH_REGEX_MAP       => env(
        EnvKey::APP_PATH_REGEX_MAP,
        [
            'num'                  => '(\d+)',
            'slug'                 => '([a-zA-Z0-9-]+)',
            'alpha'                => '([a-zA-Z]+)',
            'alpha-lowercase'      => '([a-z]+)',
            'alpha-uppercase'      => '([A-Z]+)',
            'alpha-num'            => '([a-zA-Z0-9]+)',
            'alpha-num-underscore' => '(\w+)',
        ]
    ),
];
