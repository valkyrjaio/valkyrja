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
    'coreProviders'             => env()::CONTAINER_CORE_PROVIDERS ?? [
            Valkyrja\Annotations\AnnotationsParser::class,
            Valkyrja\Annotations\Annotations::class,
            Valkyrja\Console\Console::class,
            Valkyrja\Console\Kernel::class,
            Valkyrja\Console\Input\Input::class,
            Valkyrja\Console\Output\Output::class,
            Valkyrja\Console\Output\OutputFormatter::class,
            Valkyrja\Console\Annotations\CommandAnnotations::class,
            Valkyrja\Container\Annotations\ContainerAnnotations::class,
            Valkyrja\Events\Annotations\ListenerAnnotations::class,
            Valkyrja\Filesystem\Filesystem::class,
            Valkyrja\Http\Client::class,
            Valkyrja\Http\Kernel::class,
            Valkyrja\Http\Request::class,
            Valkyrja\Http\JsonResponse::class,
            Valkyrja\Http\RedirectResponse::class,
            Valkyrja\Http\Response::class,
            Valkyrja\Http\ResponseBuilder::class,
            Valkyrja\Logger\Providers\LoggerServiceProvider::class,
            Valkyrja\Path\PathGenerator::class,
            Valkyrja\Path\PathParser::class,
            Valkyrja\Routing\Router::class,
            Valkyrja\Routing\Annotations\RouteAnnotations::class,
            Valkyrja\Session\Session::class,
            Valkyrja\View\View::class,
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
    'filePath'                  => env()::CONTAINER_FILE_PATH ?? Directory::bootstrapPath('container.php'),

    /*
     *-------------------------------------------------------------------------
     * Container Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env()::CONTAINER_CACHE_FILE_PATH ?? Directory::cachePath('container.php'),

    /*
     *-------------------------------------------------------------------------
     * Container Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCacheFile'              => env()::CONTAINER_USE_CACHE_FILE ?? false,
];
