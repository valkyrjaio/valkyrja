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
 * Events Configuration
 *-------------------------------------------------------------------------
 *
 * Events are a nifty way to tie into certain happenings throughout the
 * application. Found here are all the configurations required to make
 * events work without a hitch.
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Events Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::USE_ANNOTATIONS             => env(EnvKey::EVENTS_USE_ANNOTATIONS, false),

    /*
     *-------------------------------------------------------------------------
     * Events Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::USE_ANNOTATIONS_EXCLUSIVELY => env(EnvKey::EVENTS_USE_ANNOTATIONS_EXCLUSIVELY, false),

    /*
     *-------------------------------------------------------------------------
     * Events Annotation Classes
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::CLASSES                     => env(EnvKey::EVENTS_CLASSES, []),

    /*
     *-------------------------------------------------------------------------
     * Events Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::FILE_PATH                   => env(EnvKey::EVENTS_FILE_PATH, bootstrapPath('events.php')),

    /*
     *-------------------------------------------------------------------------
     * Events Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::CACHE_FILE_PATH             => env(EnvKey::EVENTS_CACHE_FILE_PATH, cachePath('events.php')),

    /*
     *-------------------------------------------------------------------------
     * Events Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     */
    CKP::USE_CACHE                   => env(EnvKey::EVENTS_USE_CACHE_FILE, false),
];
