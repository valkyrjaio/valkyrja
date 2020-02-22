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

use Valkyrja\Annotation\Enums\Config;
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
    CKP::MAP       => env(EnvKey::ANNOTATIONS_MAP, Config::MAP),

    /*
     *-------------------------------------------------------------------------
     * Annotations Aliases
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ALIASES   => env(EnvKey::ANNOTATIONS_ALIASES, Config::ALIASES),
];
