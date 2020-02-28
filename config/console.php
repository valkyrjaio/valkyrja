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
use Valkyrja\Console\Enums\Config;

return [
    /*
     *-------------------------------------------------------------------------
     * Console Command Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PROVIDERS                   => env(EnvKey::CONSOLE_PROVIDERS, Config::PROVIDERS),

    /*
     *-------------------------------------------------------------------------
     * Console Dev Command Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEV_PROVIDERS               => env(EnvKey::CONSOLE_DEV_PROVIDERS, Config::DEV_PROVIDERS),

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
     * Console Annotated Handlers
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
    CKP::FILE_PATH                   => env(EnvKey::CONSOLE_FILE_PATH, commandsPath('default.php')),

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
