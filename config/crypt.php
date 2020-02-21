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
 * Crypt Configuration
 *-------------------------------------------------------------------------
 *
 * Cryptography configurations for securing data.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Crypt Key
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::KEY      => env(EnvKey::CRYPT_KEY, 'default_key_phrase'),

    /*
     *-------------------------------------------------------------------------
     * Crypt Key Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::KEY_PATH => env(EnvKey::CRYPT_KEY_PATH, null),
];
