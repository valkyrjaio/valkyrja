<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Annotations\Providers\AnnotationsServiceProvider;
use Valkyrja\Console\Providers\ConsoleServiceProvider;
use Valkyrja\Filesystem\Providers\FilesystemServiceProvider;
use Valkyrja\Http\Providers\ClientServiceProvider;
use Valkyrja\Http\Providers\HttpServiceProvider;
use Valkyrja\Http\Providers\JsonResponseServiceProvider;
use Valkyrja\Http\Providers\RedirectResponseServiceProvider;
use Valkyrja\Http\Providers\ResponseBuilderServiceProvider;
use Valkyrja\Logger\Providers\LoggerServiceProvider;
use Valkyrja\Path\Providers\PathServiceProvider;
use Valkyrja\Routing\Providers\RoutingServiceProvider;
use Valkyrja\Session\Providers\SessionServiceProvider;
use Valkyrja\Support\Directory;
use Valkyrja\View\Providers\ViewServiceProvider;

/*
 *-------------------------------------------------------------------------
 * Container Configuration
 *-------------------------------------------------------------------------
 *
 * The container is the go to place for any type of service the
 * application may need when it is running. All configurations
 * necessary to make it run correctly can be found here.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Container Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'providers'                 => env()::CONTAINER_PROVIDERS ?? [],

    /*
     *-------------------------------------------------------------------------
     * Container Core Components Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'coreProviders'             => env()::CONTAINER_APP_PROVIDERS ?? [
            AnnotationsServiceProvider::class,
            ClientServiceProvider::class,
            ConsoleServiceProvider::class,
            FilesystemServiceProvider::class,
            HttpServiceProvider::class,
            JsonResponseServiceProvider::class,
            LoggerServiceProvider::class,
            PathServiceProvider::class,
            RedirectResponseServiceProvider::class,
            ResponseBuilderServiceProvider::class,
            RoutingServiceProvider::class,
            SessionServiceProvider::class,
            ViewServiceProvider::class,
        ],

    /*
     *-------------------------------------------------------------------------
     * Container Dev Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'devProviders'              => env()::CONTAINER_DEV_PROVIDERS ?? [],

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotations'            => env()::CONTAINER_USE_ANNOTATIONS ?? false,

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotationsExclusively' => env()::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'services'                  => env()::CONTAINER_SERVICES ?? [],

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Context Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'contextServices'           => env()::CONTAINER_CONTEXT_SERVICES ?? [],

    /*
     *-------------------------------------------------------------------------
     * Container Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'                  => env()::CONTAINER_FILE_PATH ?? Directory::basePath('bootstrap/container.php'),

    /*
     *-------------------------------------------------------------------------
     * Container Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env()::CONTAINER_CACHE_FILE_PATH ?? Directory::storagePath('framework/cache/container.php'),

    /*
     *-------------------------------------------------------------------------
     * Container Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCacheFile'              => env()::CONTAINER_USE_CACHE_FILE ?? true,
];
