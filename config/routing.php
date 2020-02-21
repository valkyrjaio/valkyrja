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
 * Routing Configuration
 *-------------------------------------------------------------------------
 *
 * A pretty big part of getting a user what they've requested is being
 * able to properly route a request through your application. In
 * order to do that you'll need to configure it. Lucky for you
 * all the configurations for routing can be found here.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Routing Use Trailing Slash
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::TRAILING_SLASH              => env(EnvKey::ROUTING_TRAILING_SLASH, false),

    /*
     *-------------------------------------------------------------------------
     * Routing Use Absolute Urls
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ABSOLUTE_URLS           => env(EnvKey::ROUTING_USE_ABSOLUTE_URLS, false),

    /*
     *-------------------------------------------------------------------------
     * Routing Middleware
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::MIDDLEWARE                  => env(EnvKey::ROUTING_MIDDLEWARE, []),

    /*
     *-------------------------------------------------------------------------
     * Routing Middleware Groups
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::MIDDLEWARE_GROUPS           => env(EnvKey::ROUTING_MIDDLEWARE_GROUPS, []),

    /*
     *-------------------------------------------------------------------------
     * Routing Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS             => env(EnvKey::ROUTING_USE_ANNOTATIONS, false),

    /*
     *-------------------------------------------------------------------------
     * Routing Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS_EXCLUSIVELY => env(EnvKey::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY, false),

    /*
     *-------------------------------------------------------------------------
     * Routing Annotation Classes
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONTROLLERS                 => env(EnvKey::ROUTING_CONTROLLERS, []),

    /*
     *-------------------------------------------------------------------------
     * Routing Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::FILE_PATH                   => env(EnvKey::ROUTING_FILE_PATH, routesPath('default.php')),

    /*
     *-------------------------------------------------------------------------
     * Routing Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CACHE_FILE_PATH             => env(EnvKey::ROUTING_CACHE_FILE_PATH, cachePath('routes.php')),

    /*
     *-------------------------------------------------------------------------
     * Routing Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_CACHE                   => env(EnvKey::ROUTING_USE_CACHE_FILE, false),
];
