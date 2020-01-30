<?php

declare(strict_types = 1);

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
 * Session Configuration
 *-------------------------------------------------------------------------
 *
 * You'll need to keep track of some stuff across requests, and that's
 * where the session comes in handy. Here you'll find all necessary
 * configurations to make the session work properly.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Session Id
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ID   => env(EnvKey::SESSION_ID),

    /*
     *-------------------------------------------------------------------------
     * Session Name
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::NAME => env(EnvKey::SESSION_NAME),
];
