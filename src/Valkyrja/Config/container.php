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
    'providers'                 => env()::CONTAINER_PROVIDERS ?? [
            Valkyrja\Annotations\AnnotationsParserImpl::class,
            Valkyrja\Annotations\AnnotationsImpl::class,
            Valkyrja\Client\GuzzleClient::class,
            Valkyrja\Console\ConsoleImpl::class,
            Valkyrja\Console\KernelImpl::class,
            Valkyrja\Console\Input\InputImpl::class,
            Valkyrja\Console\Output\OutputImpl::class,
            Valkyrja\Console\Output\OutputFormatterImpl::class,
            Valkyrja\Console\Annotations\CommandAnnotationsImpl::class,
            Valkyrja\Container\Annotations\ContainerAnnotationsImpl::class,
            Valkyrja\Events\Annotations\ListenerAnnotationsImpl::class,
            Valkyrja\Filesystem\FlyFilesystem::class,
            Valkyrja\Http\KernelImpl::class,
            Valkyrja\Http\RequestImpl::class,
            Valkyrja\Http\JsonResponseImpl::class,
            Valkyrja\Http\RedirectResponseImpl::class,
            Valkyrja\Http\ResponseImpl::class,
            Valkyrja\Http\ResponseBuilderImpl::class,
            Valkyrja\Logger\Providers\LoggerServiceProvider::class,
            Valkyrja\Path\PathGeneratorImpl::class,
            Valkyrja\Path\PathParserImpl::class,
            Valkyrja\Routing\RouterImpl::class,
            Valkyrja\Routing\Annotations\RouteAnnotationsImpl::class,
            Valkyrja\Session\NativeSession::class,
            Valkyrja\View\PhpView::class,
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
