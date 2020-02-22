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
 * Container Configuration
 *-------------------------------------------------------------------------
 *
 * The container is the go to place for any type of service the
 * application may need when it is running. All configurations
 * necessary to make it run correctly can be found here.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Enums\Config;

return [
    /*
     *-------------------------------------------------------------------------
     * Container Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PROVIDERS                   => env(EnvKey::CONTAINER_PROVIDERS, Config::PROVIDERS),

    /*
     *-------------------------------------------------------------------------
     * Container Dev Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEV_PROVIDERS               => env(EnvKey::CONTAINER_DEV_PROVIDERS, []),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS             => env(EnvKey::CONTAINER_USE_ANNOTATIONS, false),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS_EXCLUSIVELY => env(EnvKey::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY, false),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::SERVICES                    => env(EnvKey::CONTAINER_SERVICES, []),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Context Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONTEXT_SERVICES            => env(EnvKey::CONTAINER_CONTEXT_SERVICES, []),

    /*
     *-------------------------------------------------------------------------
     * Container Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::FILE_PATH                   => env(EnvKey::CONTAINER_FILE_PATH, servicesPath('default.php')),

    /*
     *-------------------------------------------------------------------------
     * Container Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CACHE_FILE_PATH             => env(EnvKey::CONTAINER_CACHE_FILE_PATH, cachePath('container.php')),

    /*
     *-------------------------------------------------------------------------
     * Container Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_CACHE                   => env(EnvKey::CONTAINER_USE_CACHE_FILE, false),
];
