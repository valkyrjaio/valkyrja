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
 * View Configuration
 *-------------------------------------------------------------------------
 *
 * Views are what provide users with something to look at and enjoy all
 * the hard work you've put into the application. Here you'll find
 * all the configurations necessary to make that work properly.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\View\Enums\Config;

return [
    /*
     *-------------------------------------------------------------------------
     * View Views Directory
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DIR     => env(EnvKey::VIEW_DIR, resourcesPath('views')),

    /*
     *-------------------------------------------------------------------------
     * View Default Engine
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ENGINE  => env(EnvKey::VIEW_ENGINE, Config::ENGINE),

    /*
     *-------------------------------------------------------------------------
     * View Default Engine
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ENGINES => env(EnvKey::VIEW_ENGINES, Config::ENGINES),

    /*
     *-------------------------------------------------------------------------
     * View Paths
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PATHS   => env(EnvKey::VIEW_PATHS, []),
];
