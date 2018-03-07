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
 * Crypt Configuration
 *-------------------------------------------------------------------------
 *
 * Cryptography configurations for securing data.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Crypt Key
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'key' => env('CRYPT_KEY', 'default_key_phrase'),

    /*
     *-------------------------------------------------------------------------
     * Crypt Key Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'keyPath' => env('CRYPT_KEY_PATH', envPath('key')),
];
