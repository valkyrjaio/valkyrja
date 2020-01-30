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
 * Console Configuration
 *-------------------------------------------------------------------------
 *
 * The console is Valkyrja's module for working with the application
 * through the CLI. All the configurations necessary to make that
 * work can be found here.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Console Command Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PROVIDERS                   => env(
        EnvKey::CONSOLE_PROVIDERS,
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
    CKP::DEV_PROVIDERS               => env(EnvKey::CONSOLE_DEV_PROVIDERS, []),

    /*
     *-------------------------------------------------------------------------
     * Console Quiet Output
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::QUIET                       => env(EnvKey::CONSOLE_QUIET, false),

    /*
     *-------------------------------------------------------------------------
     * Console Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS             => env(EnvKey::CONSOLE_USE_ANNOTATIONS, false),

    /*
     *-------------------------------------------------------------------------
     * Console Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS_EXCLUSIVELY => env(EnvKey::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY, false),

    /*
     *-------------------------------------------------------------------------
     * Console Handlers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::HANDLERS                    => env(EnvKey::CONSOLE_HANDLERS, []),

    /*
     *-------------------------------------------------------------------------
     * Console File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::FILE_PATH                   => env(EnvKey::CONSOLE_FILE_PATH, bootstrapPath('commands.php')),

    /*
     *-------------------------------------------------------------------------
     * Console Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CACHE_FILE_PATH             => env(EnvKey::CONSOLE_CACHE_FILE_PATH, cachePath('commands.php')),

    /*
     *-------------------------------------------------------------------------
     * Console Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_CACHE                   => env(EnvKey::CONSOLE_USE_CACHE_FILE, false),
];
