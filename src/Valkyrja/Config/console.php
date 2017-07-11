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
 * Console Configuration
 *-------------------------------------------------------------------------
 *
 * The console is Valkyrja's module for working with the application
 * through the CLI. All the configurations necessary to make that
 * work can be found here.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Console Command Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'providers'                 => env(
        'CONSOLE_PROVIDERS',
        [
            Valkyrja\Config\Commands\ConfigCacheCommand::class,
            Valkyrja\Console\Commands\CacheAllCommand::class,
            Valkyrja\Console\Commands\CommandsListCommand::class,
            Valkyrja\Console\Commands\ConsoleCacheCommand::class,
            Valkyrja\Console\Commands\CommandsListForBashCommand::class,
            Valkyrja\Console\Commands\OptimizeCommand::class,
            Valkyrja\Container\Commands\ContainerCacheCommand::class,
            Valkyrja\Events\Commands\EventsCacheCommand::class,
            Valkyrja\Routing\Commands\RoutesCacheCommand::class,
            Valkyrja\Routing\Commands\RoutesListCommand::class,
        ]
    ),

    /*
     *-------------------------------------------------------------------------
     * Console Dev Command Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'devProviders'              => env('CONSOLE_DEV_PROVIDERS', []),

    /*
     *-------------------------------------------------------------------------
     * Console Quiet Output
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'quiet'                     => env('CONSOLE_QUIET', false),

    /*
     *-------------------------------------------------------------------------
     * Console Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotations'            => env('CONSOLE_USE_ANNOTATIONS', false),

    /*
     *-------------------------------------------------------------------------
     * Console Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotationsExclusively' => env(
        'CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY',
        false
    ),

    /*
     *-------------------------------------------------------------------------
     * Console Handlers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'handlers'                  => env('CONSOLE_HANDLERS', []),

    /*
     *-------------------------------------------------------------------------
     * Console File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'                  => env(
        'CONSOLE_FILE_PATH',
        bootstrapPath('commands.php')
    ),

    /*
     *-------------------------------------------------------------------------
     * Console Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env(
        'CONSOLE_CACHE_FILE_PATH',
        cachePath('commands.php')
    ),

    /*
     *-------------------------------------------------------------------------
     * Console Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCache'                  => env('CONSOLE_USE_CACHE_FILE', false),
];
