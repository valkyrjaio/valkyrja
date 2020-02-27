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

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Enums\Provider;

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
    CKP::VERSION              => env(EnvKey::APP_VERSION, Application::VERSION),

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
    CKP::HTTP_EXCEPTION_CLASS => env(EnvKey::APP_HTTP_EXCEPTION_CLASS, Provider::HTTP_EXCEPTION),

    /*
     *-------------------------------------------------------------------------
     * Application Container Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONTAINER            => env(EnvKey::APP_CONTAINER, Provider::CONTAINER),

    /*
     *-------------------------------------------------------------------------
     * Application Dispatcher Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DISPATCHER           => env(EnvKey::APP_DISPATCHER, Provider::DISPATCHER),

    /*
     *-------------------------------------------------------------------------
     * Application Events Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::EVENTS               => env(EnvKey::APP_EVENTS, Provider::EVENTS),

    /*
     *-------------------------------------------------------------------------
     * Application ExceptionHandler Class
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::EXCEPTION_HANDLER    => env(EnvKey::APP_EXCEPTION_HANDLER, Provider::EXCEPTION_HANDLER),
];
