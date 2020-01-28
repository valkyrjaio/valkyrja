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
 * Annotations Configuration
 *-------------------------------------------------------------------------
 *
 * Anything and everything to do with annotations and how they are
 * configured to work within the application can be found here.
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Annotations Enabled
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::ENABLED   => env(EnvKey::ANNOTATIONS_ENABLED, false),

    /*
     *-------------------------------------------------------------------------
     * Annotations Cache Dir
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::CACHE_DIR => env(EnvKey::ANNOTATIONS_CACHE_DIR, storagePath('vendor/annotations')),

    /*
     *-------------------------------------------------------------------------
     * Annotations Map
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::MAP       => env(
        EnvKey::ANNOTATIONS_MAP,
        [
            'Command'        => Valkyrja\Console\Annotations\Command::class,
            'Listener'       => Valkyrja\Events\Annotations\Listener::class,
            'Route'          => Valkyrja\Routing\Annotations\Route::class,
            'Service'        => Valkyrja\Container\Annotations\Service::class,
            'ServiceAlias'   => Valkyrja\Container\Annotations\ServiceAlias::class,
            'ServiceContext' => Valkyrja\Container\Annotations\ServiceContext::class,
        ]
    ),
];
