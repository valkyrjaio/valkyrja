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
 * Annotations Configuration
 *-------------------------------------------------------------------------
 *
 * Anything and everything to do with annotations and how they are
 * configured to work within the application can be found here.
 *
 */

use Valkyrja\Annotation\Enums\Annotation;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Annotations Enabled
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ENABLED   => env(EnvKey::ANNOTATIONS_ENABLED, false),

    /*
     *-------------------------------------------------------------------------
     * Annotations Cache Dir
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CACHE_DIR => env(EnvKey::ANNOTATIONS_CACHE_DIR, storagePath('vendor/annotations')),

    /*
     *-------------------------------------------------------------------------
     * Annotations Map
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::MAP       => env(
        EnvKey::ANNOTATIONS_MAP,
        [
            CKP::COMMAND         => Annotation::COMMAND,
            CKP::LISTENER        => Annotation::LISTENER,
            CKP::ROUTE           => Annotation::ROUTE,
            CKP::SERVICE         => Annotation::SERVICE,
            CKP::SERVICE_ALIAS   => Annotation::SERVICE_ALIAS,
            CKP::SERVICE_CONTEXT => Annotation::SERVICE_CONTEXT,
        ]
    ),
];
