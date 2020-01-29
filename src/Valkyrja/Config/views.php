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
 * Views Configuration
 *-------------------------------------------------------------------------
 *
 * Views are what provide users with something to look at and enjoy all
 * the hard work you've put into the application. Here you'll find
 * all the configurations necessary to make that work properly.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Views Directory
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DIR => env(EnvKey::VIEWS_DIR, resourcesPath('views')),

    /*
     *-------------------------------------------------------------------------
     * Views Paths
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PATHS  => env(EnvKey::VIEWS_PATHS, []),
];
