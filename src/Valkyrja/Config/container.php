<?php

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
    'providers'                 => env(
        'CONTAINER_PROVIDERS',
        [
            Valkyrja\Annotations\NativeAnnotationsParser::class,
            Valkyrja\Annotations\NativeAnnotations::class,
            Valkyrja\Client\GuzzleClient::class,
            Valkyrja\Console\NativeConsole::class,
            Valkyrja\Console\NativeKernel::class,
            Valkyrja\Console\Input\NativeInput::class,
            Valkyrja\Console\Output\NativeOutput::class,
            Valkyrja\Console\Output\NativeOutputFormatter::class,
            Valkyrja\Console\Annotations\NativeCommandAnnotations::class,
            Valkyrja\Container\Annotations\NativeContainerAnnotations::class,
            Valkyrja\Events\Annotations\NativeListenerAnnotations::class,
            Valkyrja\Filesystem\FlyFilesystem::class,
            Valkyrja\Http\NativeKernel::class,
            Valkyrja\Http\NativeRequest::class,
            Valkyrja\Http\NativeJsonResponse::class,
            Valkyrja\Http\NativeRedirectResponse::class,
            Valkyrja\Http\NativeResponse::class,
            Valkyrja\Http\NativeResponseBuilder::class,
            Valkyrja\Logger\Providers\LoggerServiceProvider::class,
            Valkyrja\ORM\NativeEntityManager::class,
            Valkyrja\Path\NativePathGenerator::class,
            Valkyrja\Path\NativePathParser::class,
            Valkyrja\Routing\NativeRouter::class,
            Valkyrja\Routing\Annotations\NativeRouteAnnotations::class,
            Valkyrja\Session\NativeSession::class,
            Valkyrja\View\PhpView::class,
        ]
    ),

    /*
     *-------------------------------------------------------------------------
     * Container Dev Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'devProviders'              => env('CONTAINER_DEV_PROVIDERS', []),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotations'            => env('CONTAINER_USE_ANNOTATIONS', false),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotationsExclusively' => env(
        'CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY',
        false
    ),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'services'                  => env('CONTAINER_SERVICES', []),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Context Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'contextServices'           => env('CONTAINER_CONTEXT_SERVICES', []),

    /*
     *-------------------------------------------------------------------------
     * Container Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'                  => env(
        'CONTAINER_FILE_PATH',
        bootstrapPath('container.php')
    ),

    /*
     *-------------------------------------------------------------------------
     * Container Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env(
        'CONTAINER_CACHE_FILE_PATH',
        cachePath('container.php')
    ),

    /*
     *-------------------------------------------------------------------------
     * Container Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCache'                  => env('CONTAINER_USE_CACHE_FILE', false),
];
