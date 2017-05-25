<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Support\Directory;

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
return [
    /*
     *-------------------------------------------------------------------------
     * Routing Use Trailing Slash
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'trailingSlash'             => env()::ROUTING_TRAILING_SLASH ?? false,

    /*
     *-------------------------------------------------------------------------
     * Routing Use Absolute Urls
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAbsoluteUrls'           => env()::ROUTING_USE_ABSOLUTE_URLS ?? false,

    /*
     *-------------------------------------------------------------------------
     * Routing Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotations'            => env()::ROUTING_USE_ANNOTATIONS ?? false,

    /*
     *-------------------------------------------------------------------------
     * Routing Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotationsExclusively' => env()::ROUTING_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

    /*
     *-------------------------------------------------------------------------
     * Routing Annotation Classes
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'controllers'               => env()::ROUTING_CONTROLLERS ?? [],

    /*
     *-------------------------------------------------------------------------
     * Routing Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'                  => env()::ROUTING_FILE_PATH ?? Directory::routesPath('routes.php'),

    /*
     *-------------------------------------------------------------------------
     * Routing Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env()::ROUTING_CACHE_FILE_PATH ?? Directory::cachePath('routes.php'),

    /*
     *-------------------------------------------------------------------------
     * Routing Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCacheFile'              => env()::ROUTING_USE_CACHE_FILE ?? true,
];
